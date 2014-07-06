<?php

class AccountController extends BaseController
{

    public static function getIndex()
    {
        if (Sentry::check()) {
            return View::make('account', ['title' => 'Settings']);
        } else {
            if (Input::has('username') && Input::has('password')) {
                $username = Input::get('username');
                $password = Input::get('password');
                $input_remember = Input::get('remember');
                $remember = false;
                if (isset($input_remember) && $input_remember == true) {
                    $remember = true;
                }
                try {
                    // Login credentials
                    $credentials = array(
                        'username' => $username,
                        'password' => $password,
                    );

                    // Authenticate the user
                    $user = Sentry::authenticate($credentials, $remember);
                    return Redirect::to('account');
                } catch (Cartalyst\Sentry\Users\WrongPasswordException $e) {
                    return View::make('account')->nest('sign_in_form', 'child.signin')
                        ->nest('update_msg', 'child.alerts', array('msg_type' => 'warning', 'msg' => 'Wrong password, try again.'));
                } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
                    return View::make('account')->nest('sign_in_form', 'child.signin')
                        ->nest('update_msg', 'child.alerts', array('msg_type' => 'warning', 'msg' => 'User was not found.'));
                } catch (Cartalyst\Sentry\Users\UserNotActivatedException $e) {
                    return View::make('account')->nest('sign_in_form', 'child.signin')
                        ->nest('update_msg', 'child.alerts', array('msg_type' => 'warning', 'msg' => 'User is not activated.'));
                } catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e) {
                    return View::make('account')->nest('sign_in_form', 'child.signin')
                        ->nest('update_msg', 'child.alerts', array('msg_type' => 'warning', 'msg' => 'User is suspended.'));
                } catch (Cartalyst\Sentry\Throttling\UserBannedException $e) {
                    return View::make('account')->nest('sign_in_form', 'child.signin')
                        ->nest('update_msg', 'child.alerts', array('msg_type' => 'warning', 'msg' => 'User is banned!'));
                }
            } else {
                return View::make('account', ['title' => 'Sign in'])->nest('sign_in_form', 'child.signin');
            }
        }
    }

    public static function getMyanime()
    {
        return View::make('myanime', ['title' => 'Myanime']);
    }

    public static function getRegister()
    {
        if (Sentry::check()) {
            return Redirect::to('account');
        } else {
            if (Input::has('username') && Input::has('password') && Input::has('email')) {
                try {
                    $username = Input::get('username');
                    $email = Input::get('email');
                    $password = Input::get('password');
                    $user = Sentry::register(array(
                        'username' => $username,
                        'email' => $email,
                        'password' => $password,
                        'activated' => true,
                    ));
                    return View::make('account', ['title' => 'Sign up'])->nest('register_form', 'child.register')
                        ->nest('update_msg', 'child.alerts', array('msg_type' => 'success', 'msg' => 'You have been registered as <strong>' . $username . '</strong>, feel free to sign in' . link_to('account', 'here') . '.'));
                } catch (Cartalyst\Sentry\Users\UserExistsException $e) {
                    return View::make('account', ['title' => 'Sign up'])->nest('register_form', 'child.register')
                        ->nest('update_msg', 'child.alerts', array('msg_type' => 'warning', 'msg' => 'Username already exists!'));
                }
            } else {
                return View::make('account', ['title' => 'Sign up'])->nest('register_form', 'child.register');
            }
        }
    }

    public static function getLogout()
    {
        Sentry::logout();
        return Redirect::to('account');
    }

    public static function updateScrapeUrl()
    {
        if (Input::has('anime_id')) {
            $url = ScrapeUrl::firstOrNew(array('anime_id' => Input::get('anime_id')));
            if (Input::has('suffix_animerush'))
                $url->suffix_animerush = Input::get('suffix_animerush');
            if (Input::has('suffix_rawranime'))
                $url->suffix_rawranime = Input::get('suffix_rawranime');
            if (Input::has('othername'))
                $url->othername = Input::get('othername');
            $url->save();
            return $url;
        }
        return 'anime_id not set';
    }

    public static function updateThumbnail()
    {
        if (Input::has('anime_id')) {
            $anime = Anime::findOrFail(Input::get('anime_id'));
            $anime->thumbnail = Input::get('thumbnail');
        }
        return 'anime_id not set';
    }

}