<?php

class UserLibrary extends Eloquent
{

    protected $table = 'user_anime_library';
    protected $primaryKey = 'user_id';
    protected $fillable = array('anime_id', 'is_fav');

}