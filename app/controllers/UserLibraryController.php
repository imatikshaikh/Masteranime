<?php
/**
 * Created by PhpStorm.
 * User: Lorenzo
 * Date: 13/08/14
 * Time: 9:53
 */
class UserLibraryController extends BaseController {

    public function actionFavorite() {
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
}