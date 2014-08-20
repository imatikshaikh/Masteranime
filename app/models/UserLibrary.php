<?php

class UserLibrary extends Eloquent
{

    protected $table = 'user_anime_library';
    protected $primaryKey = 'id';
    protected $fillable = array('user_id', 'anime_id', 'is_fav');

    public static function getFavorite($anime, $user)
    {
        return UserLibrary::whereRaw('anime_id = ? and user_id = ? and is_fav = ?', array($anime, $user, 1))->first();
    }
}