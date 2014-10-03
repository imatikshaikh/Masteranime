<?php

/**
 * Created by PhpStorm.
 * User: Lorenzo
 * Date: 13/08/14
 * Time: 9:53
 *
 * 6 = Plan to watch
 */
class UserLibraryController extends BaseController
{

    public function updateLibrary($user_id, $anime_id, $library_status)
    {
        $anime = Anime::findOrFail($anime_id, array("id", "name"));
        $row = UserLibrary::firstOrNew(
            array(
                'user_id' => $user_id,
                'anime_id' => $anime->id
            )
        );
        $row->library_status = $library_status;
        $row->save();
        return $anime->name;
    }

    public function addPlanToWatch()
    {
        if (Input::has("anime_id") && Sentry::check()) {
            $anime_id = Input::get("anime_id");
            $user_id = Sentry::getUser()->id;
            if (UserLibrary::user($user_id)->anime($anime_id)->status(6)->exists()) {
                $anime = $this->updateLibrary($user_id, $anime_id, 0);
                return View::make('child.alerts', array('msg' => 'Removed from plan to watch: ' . $anime));
            }
            $anime = $this->updateLibrary($user_id, $anime_id, 6);
            return View::make('child.alerts', array('msg_type' => 'info', 'msg' => 'You plan to watch: ' . $anime));
        }
        return View::make('child.alerts', array('msg_type' => 'info', 'msg' => 'Please Sign-in/Sign-up to use this function!'));
    }

    public function addFavorite()
    {
        $user = Input::get('user_id');
        $anime = Input::get('anime_id');
        $favorite = UserLibrary::getFavorite($anime, $user);
        if (empty($favorite)) {
            $row = UserLibrary::firstOrNew(
                array(
                    'user_id' => $user,
                    'anime_id' => $anime
                )
            );
            $row->is_fav = true;
            $row->save();
            return '<a data-toggle="tooltip" title="remove from favorites"><input type="hidden" name="user_id" value="' . $user . '"><input type="hidden" name="anime_id" value="' . $anime . '"><span class="icon-heart icon-large met_gray_icon"></span></a>';
        } else {
            $favorite->delete();
            return '<a data-toggle="tooltip" title="add to favorites"><input type="hidden" name="user_id" value="' . $user . '"><input type="hidden" name="anime_id" value="' . $anime . '"><span class="icon-heart icon-large"></span></a>';
        }
        return '<a data-toggle="tooltip" title="add to favorites"><input type="hidden" name="0" value="0"><input type="hidden" name="anime_id" value="' . $anime . '"><span class="icon-heart icon-large"></span></a>';
    }

    public function addWatched()
    {
        $anime_id = Input::get("anime_id");
        $episode_id = Input::get("episode_id");
        $completed = Input::get("completed");
        if (Sentry::check()) {
            if (!empty($anime_id) && !empty($episode_id)) {
                $user_id = Sentry::getUser()->id;
                $mirrors = MasterAnime::getEpisode($anime_id, $episode_id);
                if (!empty($mirrors) && count($mirrors) > 0) {
                    $watched = UserLibrary::firstOrNew(
                        array(
                            'user_id' => $user_id,
                            'anime_id' => $anime_id
                        )
                    );
                    if (empty($watched->last_watched_episode) || $watched->last_watched_episode < $episode_id) {
                        MasterAnime::addSocialList($anime_id, $episode_id, $completed);
                        $watched->last_watched_episode = $episode_id;
                        $date = new \DateTime;
                        $watched->last_watched_time = $date;
                        $watched->save();
                        return View::make('child.alerts', array('msg_type' => 'success', 'msg' => 'Added episode ' . $episode_id . ' to last watched anime!'));
                    }
                }
            }
        } else {
            return View::make('child.alerts', array('msg_type' => 'info', 'msg' => '<a href="http://www.masterani.me/account">Sign-in</a> or <a href="http://www.masterani.me/account/register">Sign-up</a></strong> to track up to 10 last watched animes! (supports updating MAL/hummingbird)'));
        }
    }

    public function addDropped()
    {
        $anime_id = Input::get("anime_id");
        if (Sentry::check()) {
            $row = UserLibrary::firstOrNew(
                array(
                    'user_id' => Sentry::getUser()->id,
                    'anime_id' => $anime_id
                )
            );
            $row->library_status = 4;
            $row->save();
            MasterAnime::addSocialList($anime_id, 0, 4);
            return 'true';
        }
        return 'false';
    }
}