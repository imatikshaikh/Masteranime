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

    public function getSearchGenre($genre)
    {
        if (in_array($genre, AnimeWrapper::$genres)) {
            $results = Anime::whereRaw('genres LIKE ?', array('%' . $genre . '%'))->paginate(50);
            return View::make('anime.search', array('results' => $results, 'search' => $genre));
        }
        return View::make('anime.search');
    }

    public function getSearch()
    {
        if (Input::has('query')) {
            $search = Input::get('query');
            if (!empty($search) && strlen($search) >= 3 && $search != ' ') {
                $results = Anime::whereRaw('name LIKE ? or english_name LIKE ? or name_synonym_2 LIKE ? or name_synonym_3 LIKE ?', array('%' . $search . '%', '%' . $search . '%', '%' . $search . '%', '%' . $search . '%'))->paginate(20);
                if (count($results) == 1) {
                    return Redirect::to('/anime/' . $results[0]->id . '/' . str_replace(array(" ", "/", "?"), "_", $results[0]->name));
                }
                return View::make('anime.search', array('results' => $results, 'search' => $search));
            }
            return View::make('anime.search');
        }
        return View::make('anime.search');
    }

    public function getAnime($id)
    {
        $anime = Anime::find($id);
        if (empty($anime)) {
            return App::abort(404);
        }
        return View::make('anime.home', array('anime' => $anime,));
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