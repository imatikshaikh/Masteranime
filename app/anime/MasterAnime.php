<?php

class MasterAnime
{

    public static function getEpisodes($id)
    {
        $episodes = array();
        $mirrors = DB::table('mirrors')->where('anime_id', '=', $id)->select('episode')->orderBy(DB::raw('CAST(episode AS SIGNED)'), 'DESC')->get();
        foreach ($mirrors as $mirror) {
            if (empty($episodes)) {
                array_push($episodes, $mirror->episode);
            } else {
                $found = false;
                foreach ($episodes as $episode) {
                    if ($episode == $mirror->episode)
                        $found = true;
                }
                if (!$found)
                    array_push($episodes, $mirror->episode);
            }
        }
        return $episodes;
    }

    public static function getNextEpisode($id, $current)
    {
        $episodes = MasterAnime::getEpisodes($id);
        $total = count($episodes);
        if ($total > 1) {
            $search = array_search($current, $episodes);
            if ($search - 1 >= 0)
                return $episodes[$search - 1];
        }
        return 0;
    }

    public static function getPrevEpisode($id, $current)
    {
        $episodes = MasterAnime::getEpisodes($id);
        $total = count($episodes);
        if ($total > 1) {
            $search = array_search($current, $episodes);
            if ($search + 1 < $total)
                return $episodes[$search + 1];
        }
        return 0;
    }

    public static function getEpisode($id, $episode)
    {
        return Mirror::whereRaw('anime_id = ? and episode = ?', array($id, $episode))->orderBy('quality', 'DESC')->orderBy(DB::raw("field(host, 'MP4Upload','Arkvid', 'Masteranime') "), 'DESC')->get();
    }

    public static function getApiEpisode($id, $episode)
    {
        return DB::table('mirrors')->whereRaw('anime_id = ? and episode = ? and (host = ? or host = ?)', array($id, $episode, 'MP4Upload', 'Arkvid'))->select('src', 'host', 'quality')->orderBy('quality', 'DESC')->orderBy(DB::raw("field(host, 'MP4Upload','Arkvid') "), 'DESC')->get();
    }

    public static function getOngoingAnime()
    {
        return DB::table('series')->whereRaw('status = ? and type = ?', array(1, 0))->select('id', 'name', 'mal_image', 'cover')->get();
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

    public static function addSocialList($anime_id, $episode, $status)
    {
        if (Sentry::check()) {
            $anime = Anime::findOrFail($anime_id);
            $scrubbler = new AnimeDataScraper();
            if (!empty(Sentry::getUser()->mal_password)) {
                $scrubbler->addMAL(Sentry::getUser(), $anime, $episode, $status);
            }
            if (!empty(Sentry::getUser()->hum_auth)) {
                $scrubbler->addHummingbird(Sentry::getUser(), $anime, $episode, $status);
            }
            return true;
        }
        return false;
    }

    public static function manageListAccount($site, $username, $password)
    {
        if (Sentry::check()) {
            switch ($site) {
                case'myanimelist':
                    $data = new AnimeDataScraper();
                    if ($data->authMAL($username, $password)) {
                        return '<div class="alert alert-success alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong>Success!</strong> Myanimelist account has been connected.
</div>';
                    }
                    return '<div class="alert alert-error alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong>Error!</strong>Failed to connect with myanimelist (check username, password or site could be offline)
</div>';
                case 'hummingbird':
                    $data = new AnimeDataScraper();
                    if ($data->authHummingbird($username, $password)) {
                        return '<div class="alert alert-success alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong>Success!</strong> Hummingbird account has been connected.
</div>';
                    }
                    return '<div class="alert alert-error alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong>Error!</strong>Failed to connect with hummingbird (check username, password or site could be offline)
</div>';

                default:
                    return 'Site must be myanimelist or hummingbird';
            }
        }
        return 'Must be logged in.';
    }

}