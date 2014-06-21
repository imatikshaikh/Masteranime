<?php

class Latest extends Eloquent {

    protected $table = 'latest_anime';
    protected $fillable = ['anime_id', 'episode', 'name', 'img'];
    protected $primaryKey = 'anime_id';

    public static function put($anime_id, $episode, $force = false) {
        $anime = Anime::findOrFail($anime_id);
        if ($anime->status == 1 || $force) {
            $latest = Latest::whereRaw('anime_id = ? and episode = ?', array($anime->id, $episode))->get();
            if (count($latest) <= 0) {
                Latest::create(
                    array(
                        'anime_id' => $anime->id,
                        'name' => $anime->name,
                        'episode' => $episode,
                        'img' => Anime::getThumbnail($anime)
                    )
                );
            }
        }
    }

}