@extends('layout', ['description' => 'All anime available at Masterani!'])

@section('custom-js')
@parent
<script type="text/javascript">
    <?php
    if (Sentry::check()) {
        $user = Sentry::getUser();
        if ($user->isSuperUser()) {
            echo '$(document).ready(function () {
        $(\'button#update_mirrors_button\').on(\'click\', function (e) {
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
    var list;
    $(document).ready(function () {
        $('input#search').keyup(function (e) {
            if (list == null) {
                list = $("#animelist").html();
            }
            e.preventDefault();
            var keyword = $(this).val();
            if (keyword !== ' ' && keyword.length >= 3) {
                $.ajax({
                    type: "POST",
                    url: "anime/search",
                    data: { animelist: true, keyword: keyword },
                    timeout: 2000,
                    success: function (data) {
                        $("#animelist").empty().append(data);
                    }
                });
            } else {
                $("#animelist").empty().append(list);
            }
        });
    });
</script>
@stop
@section('content')
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

<div class="row-fluid " style="margin-bottom: 10px">
    <div class="span12 met_small_block">
        <div class="clearfix">
            <form class="met_contact_form">
                <div class="met_long_container">
                    <input autocomplete="off" id="search" type="text" size="50" class="met_input_text"
                           placeholder="search anime (min. 3 characters)">
                </div>
            </form>
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        {{ $update_msg or '' }}
        {{ $anime_list or ''}}
    </div>
</div>
@stop