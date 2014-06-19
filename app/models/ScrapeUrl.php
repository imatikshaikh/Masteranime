<?php

class ScrapeUrl extends Eloquent {

    protected $table = 'scrape_urls';
    protected $primaryKey = 'anime_id';
    public $timestamps = false;
    public $fillable = ['anime_id'];

}