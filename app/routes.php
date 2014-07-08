<?php
HTML::macro('menu_link', function ($routes, $phone = false) {
    /*$active = ''; if( Request::path() == $route ) {$active = ' class="active"';}*/
    $count = count($routes);
    if ($count > 1) {
        $list = '<li>' . link_to($routes[0]["route"], $routes[0]["text"]) . '<ul style="left: 0;" class="met_menu_to_left';
        $phone ? $list .= ' dl-submenu"><li class="dl-back"><a href="#">back</a></li>' : $list .= '">';
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

/*Home route*/

Route::get('/', function () {
    return View::make('home');
});
Route::get('/sitemap.xml', function () {
    return View::make('child.sitemap');
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
Route::get('/anime/update/thumbnails', function () {
    if (Sentry::check()) {
        $user = Sentry::getUser();
        if ($user->isSuperUser()) {
            return Latest::updateThumbnails();
        }
        return 'must be super user';
    }
    return 'not logged in.';
});
Route::post('/anime/managelistaccount', function () {
    if (Request::ajax()) {
        return MasterAnime::manageListAccount(Input::get("site"), Input::get("username"), Input::get("password"));
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
Route::post('/anime/scraper/url', array('as' => 'add_scrapeurl', 'uses' => 'AccountController@updateScrapeUrl'));
Route::post('/anime/update/thumbnail', array('as' => 'add_thumb', 'uses' => 'AccountController@updateThumbnail'));
Route::post('/anime/lastwatched', function () {
    if (Request::ajax()) {
        return MasterAnime::addLastwatchedAnime(Input::get('anime_id'), Input::get('episode'), Input::get('completed'));
    }
    return 'AJAX request only';
});
Route::post('/anime/favorite', function () {
    if (Request::ajax()) {
        $user_id = Input::get('user_id');
        $anime_id = Input::get('anime_id');
        $result = AnimeFavorite::actionFavorite($user_id, $anime_id);
        switch ($result['msg']) {
            case 'success':
                return $result["button"] . '<div class="pull-right alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Added!</strong></div>';

            case 'deleted':
                return $result["button"] . '<div class="pull-right alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Removed!</strong></div>';

            case 'fail':
                return $result["button"] . '<div class="pull-right alert"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> You must sign-in to add anime to favorites.</div>';
        }
    }
    return 'AJAX requests only.';
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
Route::post('/anime/update', 'AnimeController@getUpdate');
Route::get('/anime/scraper/{id}', 'AnimeController@getScraper');


/*Anime routes*/
Route::get('/anime', 'AnimeController@getIndex');
Route::get('/anime/latest', 'AnimeController@getLatest');
Route::get('/anime/{id}', 'AnimeController@getAnime');
Route::get('/anime/{id}/{name}', 'AnimeController@getAnime');
Route::get('/watch/anime/{id}/{name}/{episode}', 'AnimeController@getEpisode');
/*Account routes*/
Route::any('/account', 'AccountController@getIndex');
Route::any('/account/settings', 'AccountController@getIndex');
Route::get('/account/myanime', 'AccountController@getMyanime');
Route::get('/account/logout', 'AccountController@getLogout');
Route::any('/account/register', 'AccountController@getRegister');





