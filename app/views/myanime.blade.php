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
</style>
@stop
@section('custom-js')
@parent
<script type="text/javascript">
    var filters = [];

    $(document).ready(function () {
        var $container = $('.latest-list').isotope({
            itemSelector: '.item'
        });
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
                <input value=".next" type="checkbox"> Not finished
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
        <?php
        $series = UserLibrary::where('user_id', Sentry::getUser()->id)->where('last_watched_episode', '>', '')->where('last_watched_time', '>', \Carbon\Carbon::today()->subMonth())->orderBy('last_watched_time', 'DESC')->paginate(10);
        ?>
        @if (!empty($series) && count($series) > 0)
        <ul class="nav nav-tabs nav-stacked latest-list">
            <?php
            foreach ($series as $watched) {
                $anime = Anime::findOrFail($watched->anime_id, array('name', 'thumbnail', 'cover', 'mal_image'));
                $img = Anime::getThumbnail($anime);
                $next = MasterAnime::getNextEpisode($watched->anime_id, $watched->last_watched_episode);
                if ($next > 0) {
                    echo '<a href="' . URL::to('/watch/anime/' . $watched->anime_id . '/' . str_replace(array(" ", "/", "?"), '_', $anime->name) . '/' . $next) . '" data-toggle="tooltip" title="next episode:' . $next . '"><li class="item next">' . HTML::image($img, 'thumbnail_' . $anime->name, array('class' => 'border-radius-left')) . '<p>' . $anime->name . ' - ep. ' . $watched->last_watched_episode . '<p><h4>seen ' . Latest::time_elapsed_string($watched->last_watched_time) . '</h4></a></li>';
                } else {
                    echo '<a href="' . URL::to('/watch/anime/' . $watched->anime_id . '/' . str_replace(array(" ", "/", "?"), '_', $anime->name)) . '" data-toggle="tooltip" title="View episode index, at final episode."><li class="item">' . HTML::image($img, 'thumbnail_' . $anime->name, array('class' => 'border-radius-left')) . '<p>' . $anime->name . ' - ep. ' . $watched->last_watched_episode . '<p><h4>seen ' . Latest::time_elapsed_string($watched->last_watched_time) . '</h4></a></li>';
                }
            }
            ?>
        </ul>
        <div class="row-fluid">
            <div class="span12">
                {{ $series->links(); }}
            </div>
        </div>
        @else
        <p>
            You haven't seen any anime yet. (Anime will be added to the list after being on the video page for 7mins)
        </p>
        @endif
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