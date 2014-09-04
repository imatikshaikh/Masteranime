<?php
use Goutte\Client;

class AnimeDataScraper
{

    private $special_chars = array(":", ";", "!", "?", ".", "(", ")", ",", "'");
    public $mal_base_url = "http://myanimelist.net/api/";
    public $hum_v2_base_url = "https://vikhyat-hummingbird-v2.p.mashape.com/anime/";

    private function getXML($query, $mal = true, $account = null)
    {
        if ($mal) {
            $client = new Client();
            if (empty($account)) {
                $client->setAuth(ConnectDetails::$mal_username, ConnectDetails::$mal_password);
            } else {
                $client->setAuth($account["username"], $account["password"]);
            }
            $response = $client->request('GET', $this->mal_base_url . '' . $query);
            $xml = simplexml_load_string($response->html());
            return $xml;
        }
        return null;
    }

    private function getJSON($query, $hum = true, $anime_data = true)
    {
        if ($hum) {
            if ($anime_data) {
                $client = new Client();
                $client = $client->getClient();
                $response = $client->get($this->hum_v2_base_url . '' . $query, [
                    'headers' => ['X-Mashape-Authorization' => ConnectDetails::$mashape_key]
                ]);
                return $response->json();
            }
        }
        return null;
    }

    public function authMAL($username, $password)
    {
        if (isset($username) && isset($password)) {
            if (Sentry::check()) {
                $u = Sentry::getUser();
                $xml = $this->getXML('account/verify_credentials.xml', true, array("username" => $username, "password" => $password));
                if (!empty($xml)) {
                    if (isset($xml->user->username) && $username == $xml->user->username) {
                        $u->mal_username = $username;
                        $u->mal_password = $password;
                        return $u->save();
                    }
                }
            }
        }
        return false;
    }

    public function addMAL($user, $anime, $episode = 0, $status = 1)
    {
        if (is_object($user)) {
            $episode == 0 ? $xml = '<entry><status>' . $status . '</status></entry>' : $xml = '<entry><episode>' . $episode . '</episode><status>' . $status . '</status></entry>';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->mal_base_url . 'animelist/add/' . $anime->mal_id . '.xml');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_USERPWD, $user->mal_username . ':' . $user->mal_password);
            curl_setopt($ch, CURLOPT_POSTFIELDS, ["data" => $xml]);
            $response = curl_exec($ch);
            curl_close($ch);
            switch ($response) {
                case 'This anime is already on your list.':
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $this->mal_base_url . 'animelist/update/' . $anime->mal_id . '.xml');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                    curl_setopt($ch, CURLOPT_USERPWD, $user->mal_username . ':' . $user->mal_password);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, ["data" => $xml]);
                    curl_exec($ch);
                    curl_close($ch);
                    return '- Myanimelist: Anime has been added to your list.';
                case 'Invalid credentials':
                    return '- Myanimelist: Failed adding anime to your list (could be wrong MAL username, password or server is down)';
                default:
                    return '- Myanimelist: Anime has been added to your list.';
            }
        }
        return '';
    }

    public function authHummingbird($username, $password)
    {
        if (isset($username) && isset($password)) {
            if (Sentry::check()) {
                $u = Sentry::getUser();
                $client = new Client();
                $client = $client->getClient();
                $response = $client->post('https://hummingbirdv1.p.mashape.com/users/authenticate', [
                    'headers' => ['X-Mashape-Authorization' => ConnectDetails::$mashape_key],
                    'query' => ['username' => $username, 'password' => $password]
                ]);
                if ($response->getStatusCode() === "201" || $response->getStatusCode() === "200") {
                    $auth = $response->json();
                    if (!empty($auth) && strlen($auth) >= 20) {
                        $u->hum_username = $username;
                        $u->hum_auth = $auth;
                        return $u->save();
                    }
                }
            }
        }
        return false;
    }

    public function addHummingbird($user, $anime, $episode = 0, $status = 1)
    {
        if (is_object($user)) {
            try {
                $client = new Client();
                $client = $client->getClient();
                switch ($status) {
                    case 1:
                        $statusstr = "currently-watching";
                        break;
                    case 2:
                        $statusstr = "completed";
                        break;
                    case 4:
                        $statusstr = "dropped";
                        break;
                    default:
                        $statusstr = "currently-watching";
                        break;
                }
                $query = $episode == 0 ? ['auth_token' => $user->hum_auth, 'status' => $statusstr, 'notes' => 'Added by http://www.masterani.me/'] : ['auth_token' => $user->hum_auth, 'episodes_watched' => $episode, 'status' => $statusstr, 'notes' => 'Added by http://www.masterani.me/'];
                $response = $client->post('https://hummingbirdv1.p.mashape.com/libraries/' . $anime->hum_id, [
                    'headers' => ['X-Mashape-Authorization' => ConnectDetails::$mashape_key],
                    'query' => $query
                ]);
                if ($response->getStatusCode() === "201" || $response->getStatusCode() === "200") {
                    if ($response->json())
                        return '- Hummingbird: Added anime to your list.';
                }
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                if ($e->hasResponse()) {
                    switch ($e->getResponse()->getStatusCode()) {
                        case "401":
                            return "- Hummingbird: Wrong auth token (check username or password changes)";
                        default:
                            return '- Hummingbird: uknown error server might be down.';
                    }
                }
            }
        }
        return '';
    }

    public function get($id, $keyword, $humid = null)
    {
        $xml = $this->getXML('anime/search.xml?q=' . $keyword);
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
                        "mal-id" => (int)$final_entry->id,
                        "hum-id" => $json["id"],
                        "title" => (string)$final_entry->title,
                        "english_title" => (string)$final_entry->english,
                        "synonyms" => (string)$final_entry->synonyms,
                        "total_eps" => (int)$final_entry->episodes,
                        "type" => (string)$final_entry->type,
                        "status" => (string)$final_entry->status,
                        "start_date" => (string)$final_entry->start_date,
                        "end_date" => (string)$final_entry->end_date,
                        "synopsis" => (string)$final_entry->synopsis,
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

    public function save($data)
    {
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
        $anime->status = AnimeWrappper::getStatusInt($data["status"]);
        $anime->type = AnimeWrappper::getTypeInt($data["type"]);
        $anime->genres = $data["genres"];
        $anime->screencaps = $data["screencaps"];
        $anime->youtube_trailer_id = $data["youtube_trailer_id"];
        $anime->save();
    }
}