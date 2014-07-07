<?php

class MasterAnime
{

    private static $popular_anime = array(1);
    public static $cookie_recent_layout = "recent_layout_masteranime";

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
        for ($i = 0; $i < 1; $i++) {
            $serie = Anime::find(MasterAnime::$popular_anime[$i]);
            echo '<div class="span2 scrolled__item clearfix">
                        <a href="' . URL::to('anime/' . $serie->id . '/' . str_replace(array(" ", "/"), "_", $serie->name)) . '" class="met_our_team_photo">' . HTML::image(Anime::getCover($serie), $serie->name . '_thumbnail') . '</a>

                        <div class="met_our_team_name met_color clearfix" style="font-size: 12px;">
                            ' . $serie->name . '
                        </div>
                    </div>';
        }
    }

    public static function addToMAL($animeid, $episode, $completed)
    {
        if (!empty($animeid) && Sentry::check() && Sentry::getuser()->mal_password != null) {
            $anime = Anime::findOrFail($animeid);
            $c = new AnimeDataScraper();
            return $c->addMAL(Sentry::getUser(), $anime, $episode, $completed);
        }
        return '';
    }

    public static function addToHummingbird($animeid, $episode, $completed)
    {
        if (!empty($animeid) && Sentry::check() && Sentry::getUser()->hum_auth != null) {
            $anime = Anime::findOrFail($animeid);
            $c = new AnimeDataScraper();
            return $c->addHummingbird(Sentry::getUser(), $anime, $episode, $completed);
        }
        return '';
    }

    public static function addLastwatchedAnime($id, $episode, $completed)
    {
        if (Sentry::check()) {
            $user_id = Sentry::getUser()->id;
            $mirrors = MasterAnime::getEpisode($id, $episode);
            if (!empty($mirrors) && count($mirrors) > 0 && !empty($id) && !empty($episode)) {
                $lastwatch = LastWatched::firstOrNew(array('user_id' => $user_id, 'anime_id' => $id));
                if (empty($lastwatch->episode) || $lastwatch->episode < $episode) {
                    $mal_msg = MasterAnime::addToMAL($id, $episode, $completed);
                    $hum_msg = MasterAnime::addToHummingbird($id, $episode, $completed);
                    $lastwatch->user_id = $user_id;
                    $lastwatch->anime_id = $id;
                    $lastwatch->episode = $episode;
                    $lastwatch->save();
                    return View::make('child.alerts', array('msg_type' => 'success', 'msg' => 'Added episode ' . $episode . ' to last watched anime! ' . $mal_msg . '' . $hum_msg));
                }
            }
        }
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