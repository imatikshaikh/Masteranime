<?php

class UserLibrary extends Eloquent
{

    protected $table = 'user_anime_library';
    protected $primaryKey = 'user_id';
    protected $fillable = array('anime_id', 'is_fav');

    public static function getFavorite($anime, $user) {
        return UserLibrary::whereRaw('anime_id = ? and user_id = ?', array($anime, $user))->first();
    }
}