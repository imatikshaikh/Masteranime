@extends('layout', ['description' => 'All anime available at Masterani!'])

@section('custom-css')
@parent
<style type="text/css">
    #filters > label {
        font-size: 16px;
    }

    #filters > label > input {
        margin-top: 2px;
    }
</style>
@stop

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
<div class="row-fluid " style="margin-bottom: 10px">
    <div class="span12 met_small_block">
        <div class="clearfix">
            <form class="search_form met_contact_form" method="get" action="{{ URL::to('/anime/search') }}">
                <input class="met_input_text" id="search" name="query" autocomplete="off" type="text" maxlength="50" placeholder="anime name/synonyms.."><input class="met_button" type="submit" value="Search">
            </form>
        </div>
    </div>
</div>
<div class="clearfix">
    <h3 class="met_title_with_childs pull-left">ANIME LIST
        <span class="met_subtitle">ALL SERIES AND MOVIES AVAILABLE AT MASTERANI</span>
    </h3>
    <ul class="met_filters pull-right">
        <li><a href="#" data-filter=".ongoing">ONGOING</a></li>
        <li><a href="#" data-filter=".movie">MOVIES</a></li>
        <li><a href="#" data-filter=".favorite">FAVORITE</a></li>
        <li><a href="#" data-filter="*" class="met_color3">SHOW ALL</a></li>
    </ul>
</div>
<div class="row-fluid">
    <div class="span12">
        {{ $update_msg or '' }}
        {{ $anime_list or ''}}
    </div>
</div>
@stop