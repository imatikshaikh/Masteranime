<?php

class AnimeController extends BaseController {

    public function getIndex() {
        $is_admin = false;
        if (Sentry::check()) {
            $user = Sentry::getUser();
            if ($user->isSuperUser()) {
                $is_admin = true;
            }
        }
        return View::make('list', array('title' => 'Animelist'))->nest('anime_list', 'child.all_anime', array('is_admin' => $is_admin));
    }

    public function getAnime($id) {
        $anime = Anime::find($id);
        if (empty($anime)) {
            return View::make('anime', array('anime' => null))->nest('anime_list', 'child.all_anime');
        }
        return View::make('anime', array('anime' => $anime, 'description' => 'All information you should know about ' . $anime->name, 'title' => $anime->name. ' : Watch in HD'));
    }

    public function getUpdate() {
        $is_admin = false;
        if (Sentry::check()) {
            $user = Sentry::getUser();
            if ($user->isSuperUser()) {
                $is_admin = true;
            }
        }
        $keyword = Input::get('keyword');
        $id = Input::get('mal_id');
        $hum_id = Input::get('hum_id');
        if (!empty($keyword) && !empty($id)) {
            $db = new AnimeDataScraper();
            if (empty($hum_id)) {
                $result = $db->get($id, $keyword);
            } else {
                $result = $db->get($id, $keyword, $hum_id);
            }
            if (!empty($result)) {
                $db->save($result);
                return View::make('list', array('title' => 'Animelist'))
                    ->nest('anime_list', 'child.all_anime', array('is_admin' => $is_admin))
                    ->nest('update_msg', 'child.alerts', array('msg_type' => 'success', 'msg' => 'You succesfully updated <strong>' . $result["title"] . '</strong> to the masterani.me database.'));
            }
        } else {
            return View::make('list', array('title' => 'Animelist'))
                ->nest('anime_list', 'child.all_anime', array('is_admin' => $is_admin))
                ->nest('update_msg', 'child.alerts', array('msg_type' => 'warning', 'msg' => 'Please fill in both fields to update an anime.'));
        }
        return View::make('list', array('title' => 'Animelist'))
            ->nest('anime_list', 'child.all_anime', array('is_admin' => $is_admin))
            ->nest('update_msg', 'child.alerts', array('msg_type' => 'warning', 'msg' => 'Anime was not found.' . var_dump($result)));
    }

    public function getScraper($id) {
        if (Sentry::check()) {
            $user = Sentry::getUser();
            if ($user->isSuperUser()) {
                $data = Mirror::put($id);
                return View::make('debug', array('debug' => $data));
            }
        }
        return 'You must be a super user for this feature.';
    }

    public function getEpisode($id, $name, $episode) {
        $anime = Anime::find($id);
        if (!empty($anime)) {
            $mirrors = MasterAnime::getEpisode($anime->id, $episode);
            if (!empty($mirrors) && count($mirrors) > 0) {
                return View::make('watch', array(
                    'title' => 'Watch ' .$anime->name. ' - episode ' . $episode,
                    'anime' => $anime, 'mirrors' => $mirrors,
                    'episode' => $episode)
                );
            }
            return View::make('watch', array('title' => 'Episode not found'))
                ->nest('update_msg', 'child.alerts', array('msg_type' => 'warning', 'msg' => $anime->name . ' episode ' . $episode . ' not found in our database.'));
        }
        return View::make('watch', array('title' => 'Anime not found'))
            ->nest('update_msg', 'child.alerts', array('msg_type' => 'warning', 'msg' => 'Anime not found in our database.'));
    }

}