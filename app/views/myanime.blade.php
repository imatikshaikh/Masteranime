@extends('layout', ["footer" => false])

@section('custom-css')
@parent
<style type="text/css">
    h3 {
        text-align: center;
    }

    .gray_title {
        color: #595959
    }
</style>
@stop
@section('content')
<div class="row-fluid">
    <div class="span12">
        @if (Sentry::check())
        <?php
        echo '<div class="clearfix"><h3 class="met_title_with_childs pull-left">LAST WATCHED<span class="met_subtitle">ANIME YOU HAVE WATCHED RECENTLY</span></h3></div>';
        $all = UserLibrary::whereRaw('user_id = ? and last_watched_episode > ?', array(Sentry::getUser()->id, ''))->orderBy('last_watched_time', 'DESC')->take(10)->get();
        if (count($all) > 0) {
            echo '<ul class="nav nav-tabs nav-stacked latest-list">';
            foreach ($all as $watched) {
                $anime = Anime::findOrFail($watched->anime_id);
                $img = Anime::getThumbnail($anime);
                $next = MasterAnime::getNextEpisode($watched->anime_id, $watched->last_watched_episode);
                if ($next > 0) {
                    echo '<a href="' . URL::to('/watch/anime/' . $watched->anime_id . '/' . str_replace(array(" ", "/", "?"), '_', $anime->name) . '/' . $next) . '" data-toggle="tooltip" title="next episode: ' . $next . '"><li class="item">' . HTML::image($img, 'thumbnail_' . $anime->name, array('class' => 'border-radius-left')) . '<p>' . $anime->name . ' - ep. ' . $watched->last_watched_episode . '<p><h4>seen ' . Latest::time_elapsed_string($watched->last_watched_time) . '</h4></a></li>';
                } else {
                    echo '<a href="' . URL::to('/anime/' . $watched->anime_id . '/' . str_replace(array(" ", "/", "?"), '_', $anime->name)) . '" data-toggle="tooltip" title="View episode index, at final episode."><li class="item">' . HTML::image($img, 'thumbnail_' . $anime->name, array('class' => 'border-radius-left')) . '<p>' . $anime->name . ' - ep. ' . $watched->last_watched_episode . '<p><h4>seen ' . Latest::time_elapsed_string($watched->last_watched_time) . '</h4></a></li>';
                }
            }
            echo '</ul>';
        } else {
            echo '<p>You haven\'t seen any anime yet. (Anime will be added to the list after being on the video page for 7 mins.)</p>';
        }
        ?>
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