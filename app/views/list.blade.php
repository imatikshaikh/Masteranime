@extends('layout', ['description' => 'All anime available at masteranime!'])

@section('custom-js')
@parent
<script type="text/javascript">
    <?php
    if (Sentry::check()) {
        $user = Sentry::getUser();
        if ($user->isSuperUser()) {
            echo '$(document).ready(function () {
        $(\'#update_mirrors_button\').on(\'click\', function (e) {
            e.preventDefault();
            $(\'section\').prepend(\'<div id="loading-scraper" class="row-fluid"><div class="span12" style="text-align: center;">'.HTML::image('img/ajax-loader.gif', 'loading...') .'</div></div>\');
            var anime_id = $(this).find("input[name=\'anime_id\']").val();
            $.ajax({
                type: "POST",
                url: "anime/scraper",
                data: { anime_id: anime_id },
                timeout: 360000,
                success: function(data) {
                    $("#loading-scraper").empty().append(data);
                },
                error: function() {
                    $("#loading-scraper").empty().append(\'<div class="span12" style="text-align: center;"><p>Uknown error! failed updating mirrors!</p></div>\');
                }
            });
        });
    });';
        }
    }
    ?>
</script>
@stop
@section('content')
<div class="row-fluid">
    <div class="span12">
        {{ $update_msg or '' }}
        <?php
        if (Sentry::check()) {
            $user = Sentry::getUser();
            if ($user->isSuperUser()) {
                echo '<div class="center-text">' .
                    Form::open(array('action' => 'AnimeController@getUpdate', 'class' => 'form-inline')) . '
                    <label style="margin-right: 10px;">Anime ID, keyword & optional hum_id</label>' .
                    Form::text('mal_id', $value = null, array('class' => 'input-small', 'required' => '', 'style' => 'margin-right: 10px;')) . '' .
                    Form::text('keyword', $value = null, array('class' => 'input-large', 'required' => '', 'style' => 'margin-right: 10px;')) . '' .
                    Form::text('hum_id', $value = null, array('class' => 'input-large', 'style' => 'margin-right: 10px;')) . '' .
                    Form::submit('update', array('class' => 'btn btn-success btn-lg', 'style' => 'margin-right: 10px;')) . '' .
                    Form::close() . '</div>';
            }
        }
        ?>
    </div>
    {{ $anime_list or ''}}
</div>
@stop