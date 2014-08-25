<?php

class Latest extends Eloquent
{

    protected $table = 'latest_anime';
    protected $fillable = ['anime_id', 'episode', 'name', 'img'];
    protected $primaryKey = 'anime_id';

    public static function put($anime_id, $episode, $force = false)
    {
        $anime = Anime::findOrFail($anime_id);
        if ($anime->status == 1 || $force) {
            $latest = Latest::whereRaw('anime_id = ? and episode = ?', array($anime->id, $episode))->get();
            if (count($latest) <= 0) {
                Latest::create(
                    array(
                        'anime_id' => $anime->id,
                        'name' => $anime->name,
                        'episode' => $episode,
                        'img' => Anime::getCover($anime)
                    )
                );
            }
        }
    }

    public static function updateThumbnails()
    {
        $eps = Latest::getLatestRows(null);
        if (!empty($eps)) {
            foreach ($eps as $ep) {
                $anime = Anime::findOrFail($ep->anime_id);
                $ep->img = Anime::getCover($anime);
                $ep->save();
            }
            return 'updated thumbnails';
        }
        return 'latest episodes is empty!';
    }

    public static function getLatestRows($settings)
    {
        $query = Latest::where('created_at', '>', \Carbon\Carbon::today()->subWeek())->orderBy('created_at', 'DESC')->orderby(DB::raw('CAST(episode AS SIGNED)'), 'DESC');
        if (isset($settings["paginate"]))
            return $query->paginate($settings["paginate"]);
        return $query->get();
    }

    public static function getLatest($total = array("start" => 0, "end" => 120))
    {
        $result = "";
        $eps = Latest::getLatestRows($total);
        if (count($eps) > 0) {
            foreach ($eps as $ep) {
                $result .= '<a href="' . URL::to('/watch/anime/' . $ep->anime_id . '/' . str_replace(array(" ", " / ", " ? "), '_', $ep->name) . '/' . $ep->episode) . '">
                <div class="span3 anime_card" data-toggle="tooltip" title="' . $ep->name . '">
                    <div class="top_title">ep. ' . $ep->episode . ' - ' . Latest::time_elapsed_string($ep->created_at) . '</div>
                    ' . HTML::image($ep->img, $ep->name) . '<p>';
                if (strlen($ep->name) > 23) {
                    $name = substr($ep->name, 0, 23);
                    $result .= $name . '...';
                } else {
                    $result .= $ep->name;
                }
                $result .= '</p></div></a >';
            }
        }
        return $result;
    }

    public static function time_elapsed_string($datetime, $full = false)
    {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

}