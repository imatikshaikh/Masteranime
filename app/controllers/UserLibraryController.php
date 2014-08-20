<?php

/**
 * Created by PhpStorm.
 * User: Lorenzo
 * Date: 13/08/14
 * Time: 9:53
 */
class UserLibraryController extends BaseController
{

    public function addFavorite()
    {
        $user = Input::get('user_id');
        $anime = Input::get('anime_id');
        $favorite = UserLibrary::getFavorite($anime, $user);
        if (empty($favorite)) {
            UserLibrary::create(
                array(
                    'user_id' => $user,
                    'anime_id' => $anime,
                    'is_fav' => true
                )
            );
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
                        $mal_msg = MasterAnime::addToMAL($anime_id, $episode_id, $completed);
                        $hum_msg = MasterAnime::addToHummingbird($anime_id, $episode_id, $completed);
                        $watched->last_watched_episode = $episode_id;
                        $date = new \DateTime;
                        $watched->last_watched_time = $date;
                        $watched->save();
                        return View::make('child.alerts', array('msg_type' => 'success', 'msg' => 'Added episode ' . $episode_id . ' to last watched anime! ' . $mal_msg . '' . $hum_msg));
                    }
                }
            }
        } else {
            return View::make('child.alerts', array('msg_type' => 'info', 'msg' => '<a href="http://www.masterani.me/account">Sign-in</a> or <a href="http://www.masterani.me/account/register">Sign-up</a></strong> to track up to 10 last watched animes! (supports updating MAL/hummingbird)'));
        }
    }
}