<?php
HTML::macro('menu_link', function ($routes, $phone = false) {
    $count = count($routes);
    if ($count > 1) {
        $list = '<li>' . link_to($routes[0]["route"], $routes[0]["text"]) . '<ul style="left: 0;" class="';
        $phone ? $list .= 'dl-submenu"><li class="dl-back"><a href="#">back</a></li>' : $list .= 'dl-submenu">';
        for ($i = 1; $i < $count; $i++) {
            $list .= '<li>' . link_to($routes[$i]["route"], $routes[$i]["text"]) . '</li>';
        }
        $list .= '</ul></li>';
        return $list;
    }
    return '<li>' . link_to($routes[0]['route'], $routes[0]['text']) . '</li>';
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
App::missing(function ($exception) {
    return View::make('child.404');
});
Route::get('/', function () {
    return View::make('home');
});
Route::get('/donate', function () {
    return View::make('donate');
});
Route::get('/animehd', function () {
    return View::make('animehd');
});
Route::get('/sitemap', function () {
    $sitemap = App::make("sitemap");
    $sitemap->add('http://www.masterani.me/', '2014-07-09T20:10:00+02:00', '1.0', 'daily');
    $sitemap->add('http://www.masterani.me/latest', '2014-07-09T12:30:00+02:00', '0.9', 'daily');
    $sitemap->add('http://www.masterani.me/anime', '2014-07-09T12:30:00+02:00', '0.9', 'daily');
    $sitemap->add('http://www.masterani.me/anime/chart', '2014-07-09T12:30:00+02:00', '0.9', 'daily');
    $sitemap->add('http://www.masterani.me/animehd', '2014-07-09T12:30:00+02:00', '0.9', 'weekly');
    $animes = Anime::all();
    foreach ($animes as $anime) {
        $name = htmlspecialchars($anime->name, ENT_QUOTES, 'UTF-8');
        $sitemap->add('http://www.masterani.me/anime/' . $anime->id . '/' . str_replace(array(" ", "/", "?"), '_', $name), $anime->date_updated, '0.9', 'weekly');
    }
    return $sitemap->render();
});
Route::get('/debug', function () {
    if (Sentry::check()) {
        $user = Sentry::getUser();
        if ($user->isSuperUser()) {
            RecentAnime::scrape();
            return '<br/>DONE SCRAPING';
        }
        return 'must be super user';
    }
    return 'not logged in.';
});

/*Update & manage routes*/
Route::post('/anime/managelistaccount', function () {
    if (Request::ajax()) {
        return MasterAnime::manageListAccount(Input::get("site"), Input::get("username"), Input::get("password"));
    }
    return 'AJAX requests only';
});
Route::get('/disable/announcement', function () {
    if (Request::ajax()) {
        Cookie::queue('masterani_announcement', '1', 1440);
        return 'Announcement has been disabled for a day!';
    }
    return 'AJAX requests only';
});
Route::post('/anime/recent', function () {
    if (Request::ajax()) {
        if (Input::has('type')) {
            $gallery = Input::get('type') === 'gallery';
            MasterAnime::createRecentLayoutCookie($gallery);
            return Latest::getLatest(array("start" => 0, "end" => 12), $gallery);
        }
    }
    return 'AJAX requests only';
});
Route::post('/anime/search', function () {
    if (Request::ajax()) {
        $animelist = Input::has('animelist');
        $search_results = MasterAnime::searchAnime(Input::get('keyword'));
        if ($animelist) {
            return View::make('child.all_anime', array('search_display' => array('view' => false, 'series' => $search_results)));
        }
        return View::make('child.all_anime', array('search_display' => array('view' => true, 'series' => $search_results)));
    }
    return 'AJAX requests only';
});
Route::post('/anime/scraper', function () {
    if (Request::ajax()) {
        if (Sentry::check()) {
            $user = Sentry::getUser();
            if ($user->isSuperUser()) {
                $id = Input::get('anime_id');
                return Mirror::put($id);
            }
        }
        return 'Not allowed to perform AJAX requests!';
    }
    return 'AJAX requests only.';
});
Route::get('/anime/scraper/all', function () {
    if (Sentry::check()) {
        $user = Sentry::getUser();
        if ($user->isSuperUser()) {
            return PrintAnime::scrapeAllAnimeWithNoEpisodes();
        }
    }
    return 'Not allowed!';
});
Route::get('/anime/scraper/{id}/{startep}/{endep}', function ($id, $startep, $endep) {
    if (Sentry::check()) {
        $user = Sentry::getUser();
        if ($user->isSuperUser()) {
            return Mirror::put($id, false, $startep, $endep);
        }
    }
    return 'Not allowed!';
});
Route::get('/anime/scraper/{id}/{startep}', function ($id, $startep) {
    if (Sentry::check()) {
        $user = Sentry::getUser();
        if ($user->isSuperUser()) {
            return Mirror::put($id, false, $startep);
        }
    }
    return 'Not allowed!';
});
Route::get('/anime/scraper/{id}', function ($id) {
    if (Sentry::check()) {
        $user = Sentry::getUser();
        if ($user->isSuperUser()) {
            return Mirror::put($id);
        }
    }
    return 'Not allowed!';
});
Route::post('/watch/anime/mirror', function () {
    if (Request::ajax()) {
        $mirror = Mirror::find(Input::get('id'));
        if (!empty($mirror)) {
            return '<iframe frameborder="0" scrolling="no" width="100%" height="510" src="' . $mirror->src . '" allowfullscreen></iframe>';
        }
        return 'Could not find the mirror in our database.';
    }
    return 'AJAX requests only.';
});
Route::get('/anime/scraper/{id}', 'AnimeController@getScraper');
/*User anime lists*/
Route::get('/lists', 'ListController@getIndex');
Route::get('/lists/search', 'ListController@getSearch');
Route::get('/lists/create/new', 'ListController@getNewList');
Route::get('/lists/{id}', 'ListController@getList');
Route::get('/lists/{id}/{name}', 'ListController@getList');
Route::post('/lists/new/submit', 'ListController@submitNewList');
/*Anime manage routes*/
Route::get('/account/moderation/panel', 'AnimeManageController@getModPanel');
Route::get('/anime/manage/ongoing', 'AnimeManageController@updateOngoing');
Route::post('/anime/manage/mirror', array('as' => 'manage_single_mirror', 'uses' => 'AnimeManageController@updateMirror'));
Route::post('/anime/manage/single', array('as' => 'manage_single_anime', 'uses' => 'AnimeManageController@updateAnime'));
Route::post('/anime/manage/scraper', array('as' => 'add_scrapeurl', 'uses' => 'AnimeManageController@updateScraper'));
/*Anime routes*/
Route::get('/anime', 'AnimeController@getIndex');
Route::get('/anime/latest', 'AnimeController@getLatest');
Route::get('/anime/chart', 'AnimeController@getChart');
Route::get('/anime/{id}', 'AnimeController@getAnime');
Route::get('/anime/{id}/{name}', 'AnimeController@getAnime');
Route::get('/watch/anime/{id}/{name}/{episode}', 'AnimeController@getEpisode');
/*User library routes*/
Route::post('/userlib/favorite', 'UserLibraryController@addFavorite');
Route::post('/userlib/watched', 'UserLibraryController@addWatched');
Route::post('/userlib/drop', 'UserLibraryController@addDropped');
/*Account routes*/
Route::any('/account', 'AccountController@getIndex');
Route::any('/account/settings', 'AccountController@getIndex');
Route::get('/account/myanime', 'AccountController@getMyanime');
Route::get('/account/logout', 'AccountController@getLogout');
Route::any('/account/register', 'AccountController@getRegister');
/*API routes*/
Route::get('/api/anime/all', 'ApiController@getAll');
Route::get('/api/anime/ongoing', 'ApiController@getOngoing');
Route::get('/api/anime/search/{keyword}', 'ApiController@getSearch');
Route::get('/api/anime/latest', 'ApiController@getLatest');
Route::get('/api/anime/{id}', 'ApiController@getAnime');
Route::get('/api/anime/{id}/{episode}', 'ApiController@getEpisode');
Route::post('/api/anime/account/lastwatched', 'ApiController@setLastwatched');
Route::post('/api/anime/account/validate', 'ApiController@getValidate');






