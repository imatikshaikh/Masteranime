@extends('layout', ["footer" => false])

@section('custom-css')
@parent
<style type="text/css">
    #filters > label {
        font-size: 16px;
    }

    #filters > label > input {
        margin-top: 2px;
    }

    h3 {
        text-align: center;
    }

    .gray_title {
        color: #595959
    }

    .last-watched {
        margin-left: 0;
    }

    .last-watched > li {
        width: 100%;
        list-style: none;
        border-bottom: 1px solid #494b4b;
        margin-bottom: 5px;
    }

    .last-watched > li > p {
        color: #D9D9D9;
        float: left;
        margin-left: 10px;
        margin-top: 10px;
    }

    .last-watched > li > .time {
        color: #595959;
        font-size: 10px;
        float: left;
        margin-left: 15px;
        margin-top: 13px;
    }

    .last-watched > li > .nav-pills {
        float: right;
    }

    .last-watched > li > .nav-pills > .space {
        margin-left: 20px;
    }

    .nav-pills > li {
        height: 20px;
    }

    .nav-pills > li > a {
        height: 15px;
    }
</style>
@stop
@section('custom-js')
@parent
<script type="text/javascript">
    var filters = [':not(.finished)'];

    $(document).ready(function () {
        var $container = $('.last-watched').isotope({
            itemSelector: '.item'
        });
        $container.isotope({ filter: filters.join(", ") });
        $('#filters input[type="checkbox"]').on('click', function () {
            var filterValue = $(this).attr('value');
            if (filters.length > 0) {
                var index = filters.indexOf(filterValue);
                if (index > -1) {
                    filters.splice(index, 1);
                } else {
                    filters.push(filterValue);
                }
            } else {
                filters.push(filterValue);
            }
            $container.isotope({ filter: filters.join(", ") });
        });
    });
</script>
@stop
@section('content')
<div class="row-fluid">
    <div class="span2">
        <div class="clearfix">
            <h3 class="met_title_with_childs pull-left">FILTERS</h3>
        </div>
        <div id="filters">
            <label class="checkbox">
                <input value=":not(.finished)" type="checkbox" checked> Hide finished
            </label>
        </div>
    </div>
    <div class="span10">
        @if (Sentry::check())
        <div class="clearfix">
            <h3 class="met_title_with_childs pull-left">
                LAST WATCHED<span class="met_subtitle">ANIME YOU HAVE WATCHED RECENTLY</span>
            </h3>
        </div>
        <div id="list-anime-watched">
            <?php
            $series = UserLibrary::where('user_id', Sentry::getUser()->id)->where('last_watched_episode', '>', '')->where('library_status', '!=', 4)->where('last_watched_time', '>', \Carbon\Carbon::today()->subMonth())->orderBy('last_watched_time', 'DESC')->paginate(20);
            ?>
            {{ View::make('child.anime_watched', ['series' => $series]) }}
        </div>
        @else
        <h3 class="gray_title">Masterani features are only available for registered
            users <a
                href="http://www.masterani.me/account">Sign
                in</a>/<a href="http://www.masterani.me/account/register">Sign
                up</a> it's completely free and only takes a few seconds.</h3>
        <hr>

        <div class="row-fluid">
            <div class="span12">
                <h3 class="met_big_title">Masterani features (with account)</h3>
                <ul class="met_list">
                    <li>Autoupdating animelists services like MAL or Hummingbird</li>
                    <li>Saves up to 10 last watched anime series</li>
                    <li>Favorite anime for easy filtering</li>
                    <li>XBMC add-on / Plex plugin using our anime database</li>
                </ul>
                <p>If you have any feature suggestions feel free to leave them behind in our home discussion.</p>
            </div>
        </div>
        @endif
    </div>
</div>
@stop