<?php

class ApiController extends BaseController
{
    public function getAll()
    {
        return View::make('api.xml', ['display' => 'anime', 'content' => DB::table('series')->select('id', 'name', 'mal_image')->orderBy('name', 'ASC')->get()]);
    }

    public function getOngoing()
    {
        return View::make('api.xml', ['display' => 'anime', 'content' => DB::table('series')->whereRaw('status = ?', array(1))->select('id', 'name', 'mal_image')->orderBy('name', 'ASC')->get()]);
    }

    public function getSearch($keyword)
    {
        return View::make('api.xml', ['display' => 'anime', 'content' => DB::table('series')->whereRaw('name LIKE ? or english_name LIKE ? or name_synonym_2 LIKE ? or name_synonym_3 LIKE ?', array('%' . $keyword . '%', '%' . $keyword . '%', '%' . $keyword . '%', '%' . $keyword . '%'))->select('id', 'name', 'mal_image')->orderBy('name', 'ASC')->get()]);
    }

    public function getAnime($id)
    {
        return View::make('api.xml', ['display' => 'episodes', 'content' => MasterAnime::getEpisodes($id)]);
    }

    public function getEpisode($id, $episode)
    {
        return View::make('api.xml', ['display' => 'episode', 'content' => MasterAnime::getApiEpisode($id, $episode)]);
    }

    public function getLatest()
    {
        return View::make('api.xml', ['display' => 'latest', 'content' => Latest::orderBy('created_at', 'DESC')->orderby(DB::raw('CAST(episode AS SIGNED)'), 'DESC')->take(25)->get()]);
    }

    public function setLastWatched()
    {
        $username = Input::get("username");
        $password = Input::get("password");
        $id = Input::get("id");
        $episode = Input::get("episode");
        try {
            $credentials = array(
                'username' => $username,
                'password' => $password,
            );
            $user = Sentry::authenticate($credentials, false);
            $user_id = $user->id;
            $mirrors = MasterAnime::getEpisode($id, $episode);
            if (!empty($mirrors) && count($mirrors) > 0 && !empty($id) && !empty($episode)) {
                $lastwatch = LastWatched::firstOrNew(array('user_id' => $user_id, 'anime_id' => $id));
                if (empty($lastwatch->episode) || $lastwatch->episode < $episode) {
                    MasterAnime::addToMAL($id, $episode, 1);
                    MasterAnime::addToHummingbird($id, $episode, 1);
                    $lastwatch->user_id = $user_id;
                    $lastwatch->anime_id = $id;
                    $lastwatch->episode = $episode;
                    $lastwatch->save();
                    return 'Succesfully added episode ' . $episode . ' to last watched anime!';
                }
            }
        } catch (Cartalyst\Sentry\Users\LoginRequiredException $e) {
            return 'Username is required.';
        } catch (Cartalyst\Sentry\Users\PasswordRequiredException $e) {
            return 'Password is required.';
        } catch (Cartalyst\Sentry\Users\WrongPasswordException $e) {
            return 'Wrong password.';
        } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
            return 'User was not found.';
        } catch (Cartalyst\Sentry\Users\UserNotActivatedException $e) {
            return 'User is not activated.';
        } catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e) {
            return 'User is suspended.';
        } catch (Cartalyst\Sentry\Throttling\UserBannedException $e) {
            return 'User is banned.';
        }
    }

    public function getValidate()
    {
        $username = Input::get("username");
        $password = Input::get("password");
        try {
            $credentials = array(
                'username' => $username,
                'password' => $password,
            );
            Sentry::authenticate($credentials, false);
            return 'User validated.';
        } catch (Cartalyst\Sentry\Users\LoginRequiredException $e) {
            return 'Username is required.';
        } catch (Cartalyst\Sentry\Users\PasswordRequiredException $e) {
            return 'Password is required.';
        } catch (Cartalyst\Sentry\Users\WrongPasswordException $e) {
            return 'Wrong password.';
        } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
            return 'User was not found.';
        } catch (Cartalyst\Sentry\Users\UserNotActivatedException $e) {
            return 'User is not activated.';
        } catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e) {
            return 'User is suspended.';
        } catch (Cartalyst\Sentry\Throttling\UserBannedException $e) {
            return 'User is banned.';
        }
    }

}