<?php

class UserList extends Eloquent
{

    protected $table = 'user_lists';
    protected $primaryKey = 'id';
    protected $fillable = array('user_id', 'anime_ids', 'description', 'title');

    public static function canEdit($id)
    {
        if (Sentry::check() && (Sentry::getUser()->id == $id || Sentry::getUser()->isSuperUser())) {
            return true;
        }
        return false;
    }

}