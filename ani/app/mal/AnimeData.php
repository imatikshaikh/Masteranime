<?php
use Goutte\Client;

class AnimeDataScraper {

    private $special_chars = array(":", ";", "!", "?", ".", "(", ")", ",");
    protected $mashape_key = "h9VbGFgaOemDlC4dEH21KSnwWAJOk6jn";
    protected $mal_username = "nexpb";
    protected $mal_password = "powerbot";
    public $mal_base_url = "http://myanimelist.net/api/";
    public $hum_v2_base_url = "https://vikhyat-hummingbird-v2.p.mashape.com/anime/";

    private function getXML($query, $mal = true) {
        if ($mal) {
            $client = new Client();
            $client->setAuth($this->mal_username, $this->mal_password);
            $response = $client->request('GET', $this->mal_base_url . '' .$query);
            $xml = simplexml_load_string($response->html());
            return $xml;
        }
        return null;
    }

    private function getJSON($query, $hum = true, $anime_data = true) {
        if ($hum) {
            if ($anime_data) {
                $client = new Client();
                $client = $client->getClient();
                $response = $client->get($this->hum_v2_base_url . '' .$query, [
                    'headers' => ['X-Mashape-Authorization' => $this->mashape_key]
                ]);
                return $response->json();
            }
        }
        return null;
    }

    public function get($id, $keyword, $humid = null) {
        $xml = $this->getXML('anime/search.xml?q=' .$keyword);
        if (!empty($xml)) {
            foreach ($xml->anime->entry as $entry) {
                if ($entry->id == $id) {
                    $final_entry = $entry;
                    break;
                }
            }
            if (!empty($final_entry)) {
                if (empty($humid)) {
                    $id = str_replace(" ", "-", str_replace($this->special_chars, "", strtolower($final_entry->title)));
                    $json = $this->getJSON($id);
                } else {
                    $json = $this->getJSON($humid);
                }
                if (!empty($json)) {
                    $data = array(
                        "mal-id" => (int) $final_entry->id,
                        "hum-id" => $json["id"],
                        "title" => (string) $final_entry->title,
                        "english_title" => (string) $final_entry->english,
                        "synonyms" => (string) $final_entry->synonyms,
                        "total_eps" => (int) $final_entry->episodes,
                        "type" => (string) $final_entry->type,
                        "status" => (string) $final_entry->status,
                        "start_date" => (string) $final_entry->start_date,
                        "end_date" => (string) $final_entry->end_date,
                        "synopsis" => (string) $final_entry->synopsis,
                        "genres" => implode(', ', $json["genres"]),
                        "screencaps" => implode(', ', $json["screencaps"]),
                        "youtube_trailer_id" => $json["youtube_trailer_id"],
                        "cover" => $json["poster_image"],
                    );
                    return $data;
                }
            }
        }
        return null;
    }

    public function save($data) {
        $anime = Anime::firstOrNew(array('mal_id' => $data["mal-id"]));
        $anime->mal_id = $data["mal-id"];
        $anime->hum_id = $data["hum-id"];
        $anime->name = $data["title"];
        if ($data["title"] != $data["english_title"])
            $anime->english_name = $data["english_title"];
        if (!empty($data["synonyms"])) {
            $synonyms = explode("; ", $data["synonyms"]);
            $anime->name_synonym_2 = $synonyms[0];
            if (count($synonyms) >= 2) {
                $anime->name_synonym_3 = $synonyms[1];
            }
        }
        $anime->mal_image = $data["cover"];
        $anime->start_date = $data["start_date"];
        $anime->end_date = $data["end_date"];
        $anime->description = $data["synopsis"];
        $anime->mal_total_eps = $data["total_eps"];
        $anime->status = AnimeMisc::setStatus($data["status"]);
        $anime->type = AnimeMisc::setType($data["type"]);
        $anime->genres = $data["genres"];
        $anime->screencaps = $data["screencaps"];
        $anime->youtube_trailer_id = $data["youtube_trailer_id"];
        $anime->save();
    }

}

class AnimeMisc {

    public static function  getStatus($status) {
        switch ($status) {
            case 1:
                return 'Ongoing';

            default:
                return 'Completed';
        }
    }

    public static function getType($type) {
        switch ($type) {
            case 3:
                return 'Special';

            case 2:
                return 'Movie';

            case 1:
                return 'OVA';

            default:
                return 'TV';
        }
    }

    public static function  setStatus($status) {
        switch ($status) {
            case 'Finished Airing':
                return 0;

            case 'Currently Airing':
                return 1;

            default:
                return 0;
        }
    }

    public static function setType($type) {
        switch ($type) {
            case 'Special':
                return 3;

            case 'Movie':
                return 2;

            case 'OVA':
                return 1;

            case 'TV':
                return 0;

            default:
                return 0;
        }
    }

}