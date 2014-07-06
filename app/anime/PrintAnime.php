<?php
ini_set('output_buffering', 0);
ini_set('zlib.output_compression', 0);
ini_set('implicit_flush', 1);

set_time_limit(0);

class PrintAnime
{
    public static function scrapeAllAnimeWithNoEpisodes()
    {
        $animes = DB::table('series')->select('id', 'name')->get();
        ob_implicit_flush(1);
        foreach ($animes as $anime) {
            $mirrors = DB::table('mirrors')->where("anime_id", "=", $anime->id)->select('id')->get();
            if (empty($mirrors) && count($mirrors) === 0) {
                echo 'Started scraping anime: ' . $anime->name . '<br>';
                echo Mirror::put($anime->id);
            }
        }
        return "<br> Scraped anime!";
    }
}