<?php
use Goutte\Client;

class RecentAnime
{
    public static $url = "http://www.animerush.tv/";

    public static function get()
    {
        $client = new Client();
        $crawler = $client->request('GET', RecentAnime::$url);
        $episodes = $crawler->filter('div#episodes')->each(function (\Symfony\Component\DomCrawler\Crawler $node) {
            $subbed = $node->filter('div.episode > div > div > span.episode-sub')->extract('_text');
            $name = $node->filter('div.episode > div > h3 > a')->extract('_text');
            return array($subbed, $name);
        });
        $details = array();
        if (isset($episodes[0][0]) && isset($episodes[0][1])) {
            for ($i = 0; $i < count($episodes[0][0]); $i++) {
                $subbed = $episodes[0][0][$i];
                $h3 = $episodes[0][1][$i];
                $name_and_ep = explode(" - Episode ", $h3);
                if (count($name_and_ep) === 2) {
                    $name = $name_and_ep[0];
                    $episode = filter_var($name_and_ep[1], FILTER_SANITIZE_NUMBER_FLOAT);
                    $anime = DB::table('series')->select('id', 'name')->where('name', '=', $name)->take(1)->get();
                    $anime_id = 0;
                    if (isset($anime[0]) && is_object($anime[0])) {
                        $anime_id = $anime[0]->id;
                    } else {
                        $anime = DB::table('scrape_urls')->select('anime_id')->where('othername', '=', $name)->take(1)->get();
                        if (isset($anime[0]) && is_object($anime[0])) {
                            $anime_id = $anime[0]->anime_id;
                        }
                    }
                    if (!empty($anime_id)) {
                        $r = new PrepareAnime(array(
                            "id" => $anime_id,
                            "episode" => $episode,
                            "subbed" => $subbed
                        ));
                        array_push($details, $r);
                    } else {
                        echo 'DOESN\'T EXIST -> ' . $name . '<br/>';
                    }
                }
            }
        }
        return $details;
    }

    public static function scrape()
    {
        $episodes = RecentAnime::get();
        if (is_array($episodes) && count($episodes) > 0) {
            foreach ($episodes as $episode) {
                $episode->scrape();
            }
        }
    }

}

class PrepareAnime
{

    public $id;
    public $episode;
    public $subbed;

    public function __construct($details)
    {
        if (is_array($details)) {
            $this->id = $details["id"];
            $this->episode = $details["episode"];
            $this->subbed = ($details["subbed"] === "subbed");
        }
    }

    public function scrape()
    {
        Mirror::put($this->id, true, $this->episode);
    }

}