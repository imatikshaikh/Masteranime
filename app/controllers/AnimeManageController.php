<?php

class AnimeManageController extends BaseController
{

    public function execute($function)
    {
        if (Sentry::check() && Sentry::getUser()->isSuperUser()) {
            return $function();
        }
        return 'You have no privileges to access this page!';
    }

    public function getModPanel()
    {
        return $this->execute(function () {
            return View::make('mod_panel');
        });
    }

    public function updateOngoing()
    {
        return $this->execute(function () {
            $series = Anime::where('status', 1)->where('updated_at', '<', \Carbon\Carbon::today()->subDay())->get(array('mal_id', 'hum_id', 'name'));
            if (count($series) > 0) {
                $scraper = new AnimeDataScraper();
                $r = '';
                foreach ($series as $serie) {
                    $result = $scraper->get($serie->mal_id, str_replace(' ', '+', $serie->name), $serie->hum_id);
                    if (!empty($result)) {
                        $scraper->save($result);
                        $r .= 'Succesfully! - ' . $serie->name . '<br/>';
                    } else {
                        $r .= 'Failed! - ' . $serie->name . '<br/>';
                    }
                }
                return $r;
            }
            return 'No updateable ONGOING anime found!';
        });
    }

    public function updateThumbnails()
    {
        return $this->execute(function () {
            Latest::updateThumbnails();
            return 'Thumbnails for latest_anime have been updated!';
        });
    }

    public function updateAnime()
    {
        return $this->execute(function () {
            $keyword = Input::get('keyword');
            $mal_id = Input::get('mal_id');
            $hum_id = Input::has('hum_id') ? Input::get('hum_id') : null;
            if (!empty($keyword) && !empty($mal_id)) {
                $scraper = new AnimeDataScraper();
                $result = $scraper->get($mal_id, $keyword, $hum_id);
                if (!empty($result)) {
                    $scraper->save($result);
                    return View::make('mod_panel')->nest('update_msg', 'child.alerts', array('msg_type' => 'success', 'msg' => 'You succesfully updated <strong>' . $result["title"] . '</strong> to the masterani.me database.'));
                }
            }
            return View::make('mod_panel')->nest('update_msg', 'child.alerts', array('msg_type' => 'warning', 'msg' => 'Didn\'t find any anime matching the keyword/id!'));
        });
    }

    public function updateScraper()
    {
        return $this->execute(function () {
            if (Input::has('anime_id')) {
                $url = ScrapeUrl::firstOrNew(array('anime_id' => Input::get('anime_id')));
                if (Input::has('suffix_animerush'))
                    $url->suffix_animerush = Input::get('suffix_animerush');
                if (Input::has('suffix_rawranime'))
                    $url->suffix_rawranime = Input::get('suffix_rawranime');
                if (Input::has('othername'))
                    $url->othername = Input::get('othername');
                $url->save();
                return View::make('mod_panel')->nest('update_msg', 'child.alerts', array('msg_type' => 'success', 'msg' => 'You succesfully added a new scraper URL(s): ' . var_dump($url)));
            }
            return View::make('mod_panel')->nest('update_msg', 'child.alerts', array('msg_type' => 'warning', 'msg' => 'Please fill in the anime id.'));
        });
    }

    public function updateMirror()
    {
        return $this->execute(function () {
            $anime_id = Input::get('anime_id');
            $episode = Input::get('episode');
            $src = Input::get('src');
            $host = Input::get('host');
            $quality = Input::get('quality');
            $anime = Anime::findOrFail($anime_id)->get('id', 'name');
            Mirror::create([
                "anime_id" => $anime->id,
                "episode" => $episode,
                "src" => $src,
                "host" => $host,
                "quality" => $quality,
                "subbed" => 1
            ]);
            return View::make('mod_panel')->nest('update_msg', 'child.alerts', array('msg_type' => 'success', 'msg' => 'You succesfully added a new mirror for: ' . $anime->name));
        });
    }
}