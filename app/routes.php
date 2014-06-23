<?php

HTML::macro('menu_link', function($routes) {
    /*$active = ''; if( Request::path() == $route ) {$active = ' class="active"';}*/
    $count = count($routes);
    if ($count > 1) {
        $list = '<li>' . link_to($routes[0]["route"], $routes[0]["text"]) . '<ul style="left: 0;" class="met_menu_to_left">';
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
Route::get('/', function()
{
	return View::make('home');
});

/*Update & manage routes*/
Route::get('/anime/update/thumbnails', function()
{
    if (Sentry::check()) {
        $user = Sentry::getUser();
        if ($user->isSuperUser()) {
            return Latest::updateThumbnails();
        }
        return 'must be super user';
    }
    return 'not logged in.';
});
Route::post('/anime/scraper', function()
{
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
Route::post('/anime/scraper/url', array('as' => 'add_scrapeurl', 'uses' => 'AccountController@updateScrapeUrl'));
Route::post('/anime/favorite', function()
{
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
Route::post('/watch/anime/mirror', function()
{
    if (Request::ajax()) {
        $mirror = Mirror::find(Input::get('id'));
        if (!empty($mirror)) {
            return '<iframe frameborder="0" scrolling="no" width="100%" height="510" src="'.$mirror->src.'" allowfullscreen></iframe>';
        }
        return 'Could not find the mirror in our database.';
    }
    return 'AJAX requests only.';
});
Route::post('/anime/update', 'AnimeController@getUpdate');
Route::get('/anime/scraper/{id}', 'AnimeController@getScraper');
/*Anime routes*/
Route::get('/anime', 'AnimeController@getIndex');
Route::get('/anime/{id}', 'AnimeController@getAnime');
Route::get('/anime/{id}/{name}', 'AnimeController@getAnime');
Route::get('/watch/anime/{id}/{name}/{episode}', 'AnimeController@getEpisode');
/*Account routes*/
Route::any('/account', 'AccountController@getIndex');
Route::get('/account/logout', 'AccountController@getLogout');
Route::any('/account/register', 'AccountController@getRegister');





