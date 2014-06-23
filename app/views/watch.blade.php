@extends('layout')

@section('custom-js')
@parent
<script type="text/javascript">
$(document).ready(function() {
    $("[data-toggle=mirror]").click(function (e) {
        e.preventDefault();
        var id = $(this).find("input[name='id']").val();
        $.ajax({
            type: "POST",
            url: "../../../../watch/anime/mirror",
            data: { id: id },
            timeout: 10000,
            success: function(data) {
                $("#video").empty().append(data);
            },
            error: function() {
                $("#video").empty().append('Failed loading video, refresh the page.');
            }
        });
    });
    $("#favorite-anime a").click(function (e) {
        e.preventDefault();
        var user_id = $(this).find("input[name='user_id']").val();
        var anime_id = $(this).find("input[name='anime_id']").val();
        $.ajax({
            type: "POST",
            url: "../../../../anime/favorite",
            data: { user_id: user_id, anime_id: anime_id },
            timeout: 10000,
            success: function(data) {
                $("#favorite-anime").empty().append(data);
            }
        });
    });
});
</script>

@section('custom-js')
<style type="text/css">
    #controls-watch {
        margin-top: 10px;
    }

    #controls-watch a:hover {
        text-decoration: none;
    }

    #controls-watch a {
        margin-right: 20px;
    }
</style>
@parent
@stop

@stop

@section('content')
<div class="row-fluid">
    <div class="span12">
        {{ $update_msg or '' }}
        <?php
        if (Sentry::check()) {
            $user = Sentry::getUser();
        }
        if (isset($anime) && isset($mirrors) && isset($episode)) {
            echo '<div class="row-fluid"><div class="span9"><h3 class="met_big_title">'.$anime->name.' - episode '.$episode.'</h3>';
            echo '<div id="video"><iframe frameborder="0" scrolling="no" width="100%" height="510" src="'.$mirrors[0]->src.'" allowfullscreen></iframe></div>';
            echo '<div class="row-fluid"><div class="span12" id="controls-watch"><div id="favorite-anime" class="pull-left">';
            if (!empty($user)) {
                if (AnimeFavorite::isFavorite($user->id, $anime->id)) {
                    echo ' <a data-toggle="tooltip" title="remove from favorites"><input type="hidden" name="user_id" value="'.$user->id.'"><input type="hidden" name="anime_id" value="'.$anime->id.'"><span class="icon-heart icon-large met_gray_icon"></span></a>';
                } else {
                    echo '<a data-toggle="tooltip" title="add to favorites"><input type="hidden" name="user_id" value="'.$user->id.'"><input type="hidden" name="anime_id" value="'.$anime->id.'"><span class="icon-heart icon-large"></span></a>';
                }
            } else {
                echo '<a data-toggle="tooltip" title="add to favorites"><input type="hidden" name="user_id" value="0"><input type="hidden" name="anime_id" value="'.$anime->id.'"><span class="icon-heart icon-large"></span></a>';
            }
            echo '</div><div class="pull-right">';
                $prev = MasterAnime::getPrevEpisode($anime->id, $episode);
                if (!empty($prev)) {
                    echo '<a href="'.URL::to('/watch/anime/'.$anime->id.'/'.str_replace( ' ', '_',$anime->name).'/'.$prev).'" data-toggle="tooltip" title="previous episode"><span class="icon-arrow-left icon-large"></span></a>';
                }
                echo '<a href="'.URL::to('/anime/'.$anime->id.'/'.str_replace( ' ', '_',$anime->name)).'" data-toggle="tooltip" title="episode list"><span><i class="icon-th icon-large"></i></span></a>';
                $next = MasterAnime::getNextEpisode($anime->id, $episode);
                if (!empty($next)) {
                    echo '<a href="'.URL::to('/watch/anime/'.$anime->id.'/'.str_replace( ' ', '_',$anime->name).'/'.$next).'" data-toggle="tooltip" title="next episode"><span class="icon-arrow-right icon-large"></span></a>';
                }
            echo '</div></div></div></div>';

            echo '<div class="span3"><h3 class="met_big_title">Mirrors</h3><ul class="nav nav-tabs nav-stacked">';
                    foreach ($mirrors as $mirror) {
                        echo '<li><a href="#" data-toggle="mirror"><input type="hidden" name="id" value="'.$mirror->id.'">
                        '.$mirror->host.'<div class="pull-right">';
                        if ($mirror->quality <= 480) {
                            echo '<span class="tag-blue">SD</span>';
                        } else {
                            echo '<span class="tag-red">HD</span>';
                        }
                        echo '</div></a></li>';
                    }
            echo '</ul></div>';
            echo '</div>';
        }
        ?>
    </div>
</div>
@stop