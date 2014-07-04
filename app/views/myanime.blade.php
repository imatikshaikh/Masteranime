@extends('layout')

@section('content')
<div class="row-fluid">
    <div class="span12">
        <?php
        if (Sentry::check()) {
            echo '<div class="clearfix">
                    <h3 class="met_title_with_childs pull-left">LAST WATCHED
                        <span class="met_subtitle">ANIME YOU HAVE WATCHED RECENTLY</span>
                    </h3>
                </div>';
            $all = LastWatched::where('user_id', '=', Sentry::getUser()->id)->orderBy('updated_at', 'DESC')->take(10)->get();
            if (count($all) > 0) {
                echo '<ul class="nav nav-tabs nav-stacked latest-list">';
                foreach ($all as $anime) {
                    $ep = Anime::findOrFail($anime->anime_id);
                    if (!empty($ep)) {
                        $img = Anime::getThumbnail($ep);
                        $next = MasterAnime::getNextEpisode($anime->anime_id, $anime->episode);
                        if ($next > 0) {
                            echo '<a href="' . URL::to('/watch/anime/' . $anime->anime_id . '/' . str_replace(' ', '_', $ep->name) . '/' . $next) . '" data-toggle="tooltip" title="next episode: ' . $next . '"><li class="item">' . HTML::image($img, 'thumbnail_' . $ep->name, array('class' => 'border-radius-left')) . '<p>' . $ep->name . ' - ep. ' . $anime->episode . '<p><h4>seen ' . Latest::time_elapsed_string($anime->updated_at) . '</h4></a></li>';
                        } else {
                            echo '<a href="' . URL::to('/anime/' . $anime->anime_id . '/' . str_replace(' ', '_', $ep->name)) . '" data-toggle="tooltip" title="View episode index, at final episode."><li class="item">' . HTML::image($img, 'thumbnail_' . $ep->name, array('class' => 'border-radius-left')) . '<p>' . $ep->name . ' - ep. ' . $anime->episode . '<p><h4>seen ' . Latest::time_elapsed_string($anime->updated_at) . '</h4></a></li>';
                        }
                    }
                }
                echo '</ul>';
            } else {
                echo '<p>You haven\'t seen any anime yet.</p>';
            }
        } else {
            echo '<p>You must <a href="' . URL::to('account') . '">Sign in</a>/<a href="' . URL::to('account/register') . '">Sign up</a> to view Myanime</p>';
        }
        ?>
    </div>
</div>
@stop