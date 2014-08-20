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
                    <div class="span12">
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
                            if ($count == 0) {
                                echo '<div class="row-fluid div-cover">';
                            }
                            echo '<div class="span2 clearfix"> <a data-toggle="tooltip-bottom" title="' . $serie["anime"]->name . ' - ep. ' . ((int)$serie["episode"] + 1) . '" href="' . URL::to('anime/' . $serie["anime"]->id . '/' . str_replace(array(" ", "/"), "_", $serie["anime"]->name)) . '" class="met_our_team_photo">' . HTML::image(Anime::getCover($serie["anime"]), $serie["anime"]->name . '_cover') . '</a><div class="met_our_team_name met_color clearfix" style="font-size: 12px;">';
                            if ($serie["interval"]->d > 0) {
                                echo $serie["interval"]->d . 'days ';
                            }
                            echo $serie["interval"]->h . 'hrs ' . $serie["interval"]->i . 'mins ';
                            echo '</div></div>';
                            $count++;
                            $total_count++;
                            if ($total == $total_count || $count >= 6) {
                                $count = 0;
                                echo '</div>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop