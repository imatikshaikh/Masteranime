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

    public function scopeUser($query, $user_id)
    {
        return $query->where('user_id', '=', $user_id);
    }

    public function scopeAnime($query, $anime_id)
    {
        return $query->where('anime_id', '=', $anime_id);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('library_status', '=', $status);
    }
}