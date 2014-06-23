<?php
class MasterAnime {

    public static function getEpisodes($id) {
        $episodes = array();
        foreach (Mirror::where('anime_id', '=', $id)->orderBy(DB::raw('CAST(episode AS SIGNED)'), 'DESC')->get() as $mirror) {
            if (empty($episodes)) {
                array_push($episodes, (int) $mirror->episode);
            } else {
                $found = false;
                foreach ($episodes as $episode) {
                    if ($episode == $mirror->episode)
                        $found = true;
                }
                if (!$found)
                    array_push($episodes, (int) $mirror->episode);
            }
        }
        return $episodes;
    }

    public static function getNextEpisode($id, $current) {
        $episodes = MasterAnime::getEpisodes($id);
        $total = count($episodes);
        if ($total > 1) {
            return $current == $total ? 0 : $episodes[array_search($current+1, $episodes)];
        } 
        return 0;
    }

    public static function getPrevEpisode($id, $current) {
        $episodes = MasterAnime::getEpisodes($id);
        $total = count($episodes);
        if ($total > 1) {
            return $current == 1 ? 0 : $episodes[array_search($current-1, $episodes)];
        } 
        return 0;
    }

    public static function getEpisode($id, $episode) {
        return Mirror::whereRaw('anime_id = ? and episode = ?', array($id, $episode))->orderBy('quality', 'DESC')->orderBy(DB::raw("field(host, 'MP4Upload','Arkvid', 'Masteranime') "), 'DESC')->get();
    }

}