@extends('layout')

@section('custom-css')
@parent
<style type="text/css">
    .div-cover img {
        max-height: 290px;
        max-width: 200px;
    }
</style>
@stop
@section('content')
<div class="row-fluid">
    <div class="span12">
        <div class="row-fluid met_small_block scrolled">
            <div class="span12 scrolled__item">
                <div class="clearfix">
                    <h3 class="met_title_with_childs pull-left">ANIME CHART
                        <span class="met_subtitle">ESTIMATED TIME TILL NEXT EPISODE RELEASES</span>
                    </h3>
                </div>
                <div class="row-fluid">
                    <?php
                    function getAnime()
                    {
                        $series = MasterAnime::getOngoingAnime();
                        $anime = array();
                        foreach ($series as $serie) {
                            $latest = DB::table('latest_anime')->whereRaw('anime_id = ?', array($serie->id))->select('created_at', 'episode')->orderBy('created_at', 'DESC')->first();
                            if (!empty($latest)) {
                                $today = new DateTime();
                                $future = new DateTime($latest->created_at);
                                $future->add(new DateInterval("PT168H"));
                                $interval = $future->diff($today);
                                if ($future > $today) {
                                    $date = $interval->format('%d%H');
                                    array_push($anime, array('episode' => $latest->episode, 'interval' => $interval, 'anime' => $serie, 'sort' => $date));
                                }
                            }
                        }
                        return $anime;
                    }

                    $series = getAnime();
                    usort($series, function ($a1, $a2) {
                        return ($a1["sort"] < $a2["sort"]) ? -1 : 1;
                    });
                    $count = 0;
                    $total_count = 0;
                    $total = count($series);
                    foreach ($series as $serie) {
                        $time = '';
                        if ($serie["interval"]->d > 0) {
                            $time .= $serie["interval"]->d . 'days ';
                        }
                        $time .= $serie["interval"]->h . 'hrs ' . $serie["interval"]->i . 'mins ';
                        echo View::make('child.card_anime', array(
                            "anime_id" => $serie["anime"]->id,
                            "anime_name" => $serie["anime"]->name,
                            "anime_episode" => ((int)$serie["episode"] + 1),
                            "anime_img" => Anime::getCover($serie["anime"]),
                            "time" => $time,
                            "chart" => true
                        ));
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
@stop