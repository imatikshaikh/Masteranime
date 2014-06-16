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
            echo '</div>';

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