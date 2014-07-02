<?php

class LastWatched extends Eloquent
{

    protected $table = 'lastwatched_anime';
    public $fillable = ['anime_id', 'episode'];

}