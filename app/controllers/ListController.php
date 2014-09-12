<?php

class ListController extends BaseController
{

    public function getIndex()
    {
        return View::make('user_lists.home');
    }

    public function getList($id)
    {
        if (is_numeric($id)) {
            $list = UserList::find($id);
            if (!empty($list))
                return View::make('user_lists.list', array('list' => $list));
        }
        return App::abort(404);
    }

    public function getSearch()
    {
        $search = Input::get('query');
        if (!empty($search) && strlen($search) >= 3 && $search != ' ') {
            $results = UserList::whereRaw('title LIKE ? or description LIKE ?', array('%' . $search . '%', '%' . $search . '%'))->orderby('updated_at', 'DESC')->paginate(25);
            if (!empty($results) && count($results) > 0) {
                return View::make('user_lists.home', array("search" => $results));
            }
        }
        return View::make('user_lists.home', array("search" => false));
    }

    public function getNewList()
    {
        if (Sentry::check()) {
            return View::make('user_lists.new');
        }
        return Redirect::to('/account');
    }

    public function submitNewList()
    {
        if (Sentry::check()) {
            $title = Input::get('title');
            $description = Input::get('description');
            $anime_ids = Input::get('anime_ids');
            if (!empty($title) && !empty($description)) {
                if (!empty($anime_ids)) {
                    $arr_count = count($anime_ids) - 1;
                    $total_count = 0;
                    $count = 0;
                    $ids = "";
                    foreach ($anime_ids as $anime_id) {
                        if (is_numeric($anime_id)) {
                            if ($arr_count == $total_count) {
                                $ids .= $anime_id;
                            } else {
                                $ids .= $anime_id . ",";
                            }
                            $count++;
                        }
                        $total_count++;
                    }
                    if ($count < 7) {
                        return View::make('user_lists.home')->nest('update_msg', 'child.alerts', array('msg' => 'Minimum 7 animes in the list! (No maximum limit)'));
                    }
                    UserList::create([
                        "user_id" => Sentry::getUser()->id,
                        "title" => $title,
                        "description" => $description,
                        "anime_ids" => $ids
                    ]);
                    return View::make('user_lists.home')->nest('update_msg', 'child.alerts', array('msg_type' => 'success', 'msg' => 'Anime list has been created!'));
                } else {
                    return View::make('user_lists.home')->nest('update_msg', 'child.alerts', array('msg' => 'No anime selected!'));
                }
            } else {
                return View::make('user_lists.home')->nest('update_msg', 'child.alerts', array('msg' => 'Please, fill in the title/description!'));
            }
        }
        return Redirect::to('/account');
    }

}