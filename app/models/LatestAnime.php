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
                        'img' => Anime::getThumbnail($anime)
                    )
                );
            }
        }
    }

    public static function updateThumbnails($total = array("start" => 0, "end" => 12))
    {
        $eps = Latest::getLatestRows($total);
        if (!empty($eps)) {
            foreach ($eps as $ep) {
                $anime = Anime::findOrFail($ep->anime_id);
                $ep->img = Anime::getThumbnail($anime);
                $ep->save();
            }
            return 'updated thumbnails';
        }
        return 'latest episodes is empty!';
    }

    public static function getLatestRows($total)
    {
        if (isset($total["start"]) && isset($total["end"])) {
            return Latest::orderBy('updated_at', 'DESC')->orderby(DB::raw('CAST(episode AS SIGNED)'), 'DESC')->skip($total["start"])->take($total["end"])->get();
        } else if (isset($total["end"])) {
            return Latest::orderBy('updated_at', 'DESC')->orderby(DB::raw('CAST(episode AS SIGNED)'), 'DESC')->take($total["end"])->get();
        }
        return null;
    }

    public static function getLatest($total = array("start" => 0, "end" => 12), $galerry = true)
    {
        $result = "";
        $eps = Latest::getLatestRows($total);
        if (!empty($eps)) {
            if ($galerry) {
                $result .= '<div class="met_recent_works_carousel_wrap">
            <div class="met_recent_works_carousel clearfix scrolled met_mainpage_portfolio">';
                foreach ($eps as $ep) {
                    $result .= '<div class="met_recent_work scrolled__item';
                    $result .= ' threedcharacters">';
                    $result .= '<a href="' . URL::to('/watch/anime/' . $ep->anime_id . '/' . str_replace(array(" ", "/"), '_', $ep->name) . '/' . $ep->episode) . '" class="met_recent_work_picture_area">
                    ' . HTML::image($ep->img, 'thumbnail_' . $ep->name, array("class" => "image-latest")) . '<span><span><i class="icon-film icon-large"></i></span></span></a><aside class="clearfix"></aside>
                    <a href="' . URL::to('/watch/anime/' . $ep->anime_id . '/' . $ep->name . '/' . $ep->episode) . '" class="met_recent_work_double_title">';
                    if (strlen($ep->name) > 30) {
                        $name = substr($ep->name, 0, 30);
                        $result .= '<h3 data-toggle="tooltip" title="' . $ep->name . '">' . $name . '.. - ep. ' . $ep->episode . '</h3>';
                    } else {
                        $result .= '<h3>' . $ep->name . ' - ep. ' . $ep->episode . '</h3>';
                    }
                    $result .= '<h4>' . Latest::time_elapsed_string($ep->created_at) . '</h4></a></div>';
                }
                $result .= '</div></div>';
                $result .= HTML::script('js/caroufredsel.js') . HTML::script('js/custom.js') . HTML::script('js/jquery.onecarousel.min.js') . HTML::script('js/isotope.js');
            } else {
                $result .= '<ul class="nav nav-tabs nav-stacked latest-list" style="overflow: visible;">';
                foreach ($eps as $ep) {
                    $result .= '<li class="item"><a href="' . URL::to('watch/anime/' . $ep->anime_id . '/' . str_replace(array(" ", "/"), "_", $ep->name)) . '/' . $ep->episode . '">' . HTML::image($ep->img, 'thumbnail_' . $ep->name, array('class' => 'border-radius-left')) . '<p>' . $ep->name . ' - ep. ' . $ep->episode . '<p><h4>' . Latest::time_elapsed_string($ep->created_at) . '</h4></a></li>';
                }
                $result .= '</ul>';
                $result .= HTML::script('js/custom.js') . HTML::script('js/jquery.onecarousel.min.js') . HTML::script('js/isotope.js');
                $result .= '<script type="text/javascript">
    $(document).ready(function () {
        var $container = $(\'.latest-list\').isotope({
            itemSelector: \'.item\'
        });
        $(\'.met_filters a\').click(function () {
            var filterValue = $(this).attr(\'data-filter\');
            $container.isotope({ filter: filterValue });
        });
    });
</script>';
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