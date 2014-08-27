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
}