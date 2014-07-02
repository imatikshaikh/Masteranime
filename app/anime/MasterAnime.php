<?php

class MasterAnime
{

    private static $popular_anime = array(2, 3, 6, 7);
    public static $cookie_recent_layout = "recent_layout_masteranime";

    public static function getEpisodes($id)
    {
        $episodes = array();
        foreach (Mirror::where('anime_id', '=', $id)->orderBy(DB::raw('CAST(episode AS SIGNED)'), 'DESC')->get() as $mirror) {
            if (empty($episodes)) {
                array_push($episodes, (int)$mirror->episode);
            } else {
                $found = false;
                foreach ($episodes as $episode) {
                    if ($episode == $mirror->episode)
                        $found = true;
                }
                if (!$found)
                    array_push($episodes, (int)$mirror->episode);
            }
        }
        return $episodes;
    }

    public static function getNextEpisode($id, $current)
    {
        $episodes = MasterAnime::getEpisodes($id);
        $total = count($episodes);
        if ($total > 1) {
            return $current == $total ? 0 : $episodes[array_search($current + 1, $episodes)];
        }
        return 0;
    }

    public static function getPrevEpisode($id, $current)
    {
        $episodes = MasterAnime::getEpisodes($id);
        $total = count($episodes);
        if ($total > 1) {
            return $current == 1 ? 0 : $episodes[array_search($current - 1, $episodes)];
        }
        return 0;
    }

    public static function getEpisode($id, $episode)
    {
        return Mirror::whereRaw('anime_id = ? and episode = ?', array($id, $episode))->orderBy('quality', 'DESC')->orderBy(DB::raw("field(host, 'MP4Upload','Arkvid', 'Masteranime') "), 'DESC')->get();
    }

    public static function searchAnime($keyword)
    {
        if (strlen($keyword) >= 3 && $keyword !== ' ') {
            $animes = DB::table('series')->select('id', 'name', 'english_name', 'name_synonym_2', 'name_synonym_3', 'type', 'status')->whereRaw('name LIKE ? or english_name LIKE ? or name_synonym_2 LIKE ? or name_synonym_3 LIKE ?', array('%' . $keyword . '%', '%' . $keyword . '%', '%' . $keyword . '%', '%' . $keyword . '%'))->get();
            if (count($animes) > 0) {
                return $animes;
            }
        }
        return null;
    }

    public static function createRecentLayoutCookie($gallery)
    {
        Cookie::queue(MasterAnime::$cookie_recent_layout, $gallery, 43200);
    }

    public static function printPopularAnime()
    {
        shuffle(MasterAnime::$popular_anime);
        for ($i = 0; $i < 4; $i++) {
            $serie = Anime::find(MasterAnime::$popular_anime[$i]);
            echo '<div class="span3 scrolled__item clearfix">
                        <a href="' . URL::to('anime/' . $serie->id . '/' . str_replace(" ", "_", $serie->name)) . '" class="met_our_team_photo">' . HTML::image(Anime::getCover($serie), $serie->name . '_thumbnail') . '</a>

                        <div class="met_our_team_name met_color clearfix" style="font-size: 14px;">
                            ' . $serie->name . '
                        </div>
                    </div>';
        }
    }

    public static function addLastwatchedAnime($id, $episode)
    {
        if (Sentry::check()) {
            $user_id = Sentry::getUser()->id;
            $mirrors = MasterAnime::getEpisode($id, $episode);
            if (!empty($mirrors) && count($mirrors) > 0 && !empty($id) && !empty($episode)) {
                $lastwatch = LastWatched::firstOrNew(array('user_id' => $user_id, 'anime_id' => $id));
                $lastwatch->user_id = $user_id;
                $lastwatch->anime_id = $id;
                $lastwatch->episode = $episode;
                $lastwatch->save();
                return View::make('child.alerts', array('msg_type' => 'success', 'msg' => 'Added episode ' . $episode . ' to last watched anime!'));
            }
        }
    }

}