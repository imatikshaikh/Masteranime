<?php

class AnimeManageController extends BaseController
{

    public function updateOngoing()
    {
        if (Sentry::check() && Sentry::getUser()->isSuperUser()) {
            $series = Anime::where('status', 1)->where('updated_at', '>', \Carbon\Carbon::today()->subDay())->get(array('mal_id', 'hum_id', 'name'));
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
            return 'No ONGOING anime found!';
        }
        return 'You have no privileges to access this page!';
    }
}