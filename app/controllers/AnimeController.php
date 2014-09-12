<?php

class AnimeController extends BaseController
{

    public function getIndex()
    {
        $is_admin = false;
        if (Sentry::check()) {
            $user = Sentry::getUser();
            if ($user->isSuperUser()) {
                $is_admin = true;
            }
        }
        return View::make('list', array('title' => 'All anime'))->nest('anime_list', 'child.all_anime', array('is_admin' => $is_admin));
    }

    public function getChart()
    {
        return View::make('chart', array('title' => 'Anime chart', 'description' => 'Masterani chart shows the estimated time when airing anime will be released.'));
    }

    public function getLatest()
    {
        return View::make('latest', array('title' => 'Latest anime'));
    }

    public function getAnime($id)
    {
        $anime = Anime::find($id);
        if (empty($anime)) {
            return App::abort(404);
        }
        return View::make('anime', array('anime' => $anime, 'description' => $anime->name . ' trailer, episodes and information in HD (720p) or SD (480p)!', 'title' => 'Watch ' . $anime->name . ' in HD or SD'));
    }

    public function getScraper($id)
    {
        if (Sentry::check()) {
            $user = Sentry::getUser();
            if ($user->isSuperUser()) {
                $data = Mirror::put($id);
                return View::make('debug', array('debug' => $data));
            }
        }
        return 'You must be a super user for this feature.';
    }

    public function getEpisode($id, $name, $episode)
    {
        $anime = Anime::find($id);
        if (!empty($anime)) {
            $mirrors = MasterAnime::getEpisode($anime->id, $episode);
            if (!empty($mirrors) && count($mirrors) > 0) {
                return View::make('watch', array(
                        'title' => 'Watch ' . $anime->name . ' - episode ' . $episode,
                        'description' => 'Watch ' . $anime->name . ' episode ' . $episode . ' online in HD on desktop, tablet and mobile.',
                        'anime' => $anime, 'mirrors' => $mirrors,
                        'episode' => $episode)
                );
            }
            return App::abort(404);
        }
        return App::abort(404);
    }

}