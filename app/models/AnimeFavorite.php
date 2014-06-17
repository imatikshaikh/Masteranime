<?php

class AnimeFavorite extends Eloquent {

    protected $table = 'anime_favorites';
    protected $fillable = ['user_id', 'anime_id'];

    public static function isFavorite($userid, $animeid) {
        return AnimeFavorite::whereRaw('user_id = ? and anime_id = ?', array($userid, $animeid))->first();
    }

    public static function actionFavorite($userid, $animeid) {
        if (!empty($userid) && !empty($animeid)) {
            $fav = AnimeFavorite::isFavorite($userid, $animeid);
            if (empty($fav)) {
                AnimeFavorite::create(array(
                    'user_id' => $userid,
                    'anime_id' => $animeid
                ));
                return array(
                    'msg' => 'success',
                    'button' => ' <a data-toggle="tooltip" title="remove from favorites"><input type="hidden" name="user_id" value="'.$userid.'"><input type="hidden" name="anime_id" value="'.$animeid.'"><span class="icon-heart icon-large met_gray_icon"></span></a>'
                );
            } else {
                $fav->delete();
                return array(
                    'msg' => 'deleted',
                    'button' => '<a data-toggle="tooltip" title="add to favorites"><input type="hidden" name="user_id" value="'.$userid.'"><input type="hidden" name="anime_id" value="'.$animeid.'"><span class="icon-heart icon-large"></span></a>'
                );
            }
        }
        return array(
            'msg' => 'fail',
            'button' => '<a data-toggle="tooltip" title="add to favorites"><input type="hidden" name="0" value="0"><input type="hidden" name="anime_id" value="'.$animeid.'"><span class="icon-heart icon-large"></span></a>'
        );
    }

}