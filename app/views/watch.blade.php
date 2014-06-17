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
});
</script>
@stop

@section('content')
<div class="row-fluid">
    <div class="span12">
        {{ $update_msg or '' }}
        <?php
        if (isset($anime) && isset($mirrors) && isset($episode)) {
            echo '<div class="row-fluid"><div class="span9"><h3 class="met_big_title">'.$anime->name.' - episode '.$episode.'</h3>';
            echo '<div id="video"><iframe frameborder="0" scrolling="no" width="100%" height="510" src="'.$mirrors[0]->src.'" allowfullscreen></iframe></div>';
            echo '<div class="row-fluid"><div class="span12">';
                $prev = MasterAnime::getPrevEpisode($anime->id, $episode);
                if (!empty($prev)) {
                    echo '<a href="'.URL::to('/watch/anime/'.$anime->id.'/'.$anime->name.'/'.$prev).'" data-toggle="tooltip" title="Previous episode"><span class="icon-arrow-left icon-large"></span></a>';
                }
                echo '<a href="'.URL::to('/anime/'.$anime->id.'/'.$anime->name).'" data-toggle="tooltip" title="Episode list"><span><i class="icon-th icon-large"></i></span></a>';
                $next = MasterAnime::getNextEpisode($anime->id, $episode);
                if (!empty($next)) {
                    echo '<a href="'.URL::to('/watch/anime/'.$anime->id.'/'.$anime->name.'/'.$next.').'" data-toggle="tooltip" title="Next episode"><span class="icon-arrow-right icon-large"></span></a>';
                }
            echo '</div></div></div>';

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