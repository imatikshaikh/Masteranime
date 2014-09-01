@extends('layout')

@section('content')
<div class="row-fluid">
    <div class="span12">
        <div class="row-fluid met_small_block scrolled">
            <div class="span12 scrolled__item">
                <div class="clearfix">
                    <h3 class="met_title_with_childs pull-left">LATEST ANIME
                        <span class="met_subtitle">LATEST EPISODE UPDATES</span>
                    </h3>
                </div>
                <?php
                $latest_eps = Latest::getLatestRows(null);
                if (!empty($latest_eps) && count($latest_eps) > 0) {
                    foreach ($latest_eps as $latest_ep) {
                        echo View::make('child.card_anime', array(
                            "anime_id" => $latest_ep->anime_id,
                            "anime_name" => $latest_ep->name,
                            "anime_episode" => $latest_ep->episode,
                            "anime_img" => $latest_ep->img,
                            "time" => $latest_ep->created_at,
                            "display" => "latest"
                        ));
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>
@stop