@extends('layout')
@if (empty($anime))
@section('content')
<div class="row-fluid">
    <div class="span12">
        @include('child.alerts', array('msg' => 'This anime does not exists in our database.'))
    </div>
</div>
{{ $anime_list or ''}}
@stop
@else

@section('custom-css')
@parent
<?php
echo '<style type="text/css">
            .anime-header:before {';
$screencaps = Anime::getScreencaps($anime);
if (!empty($screencaps)) {
    $count = count($screencaps);
    $random = rand(0, $count - 1);
    echo 'background: url("' . $screencaps[$random] . '") 50% 30% no-repeat;';
} else {
    echo 'background: url("' . Anime::getCover($anime) . '") 50% 30% no-repeat;';
}
echo 'background-size: cover;
                content: "";
                position: absolute;
                min-height: 200px;
                width: 100%;
                -webkit-filter: blur(3px);
                -moz-filter: blur(3px);
                -o-filter: blur(3px);
                -ms-filter: blur(3px);
                filter: blur(3px);
                z-index: 0;
            }
        </style>';
?>
@stop
@section('anime-header')
<div class="anime-header">
    <div class="anime-header-wrap-dark">
        <div class="met_content">
            <div class="row-fluid" style="margin-bottom: 0">
                <div class="span2">
                    <div class="anime-header-avatar">
                        <?php
                        echo '<img class="anime-header-avatar-image" src="' . Anime::getCover($anime) . '" alt="cover_anime"/>';
                        ?>
                    </div>
                </div>
                <div class="span10">
                    <?php
                    if ($anime->status == 1) {
                        echo '<h1><span data-toggle="tooltip" title="ongoing">';
                    } else if ($anime->type == 2) {
                        echo '<h1><span data-toggle="tooltip" title="movie">';
                    } else {
                        echo '<h1><span data-toggle="tooltip" title="completed">';
                    }
                    ?>
                    {{ $anime->name }}</span></h1>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="row-fluid" style="margin-top: -60px;">
    <div class="span12" style="text-align: center">
        <div class="btn-group" id="anime-button-action">
            <?php echo '<a target="_blank" href="http://myanimelist.net/anime/' . $anime->mal_id . '" data-toggle="tooltip" title="view on MyAnimeList" class="met_button border-radius-left">'; ?>
            {{ HTML::image('img/mal-logo.png', 'myanimelist Logo', array("style" => "margin: -8px 0px;")) }}
            </a>
            <?php echo '<a target="_blank" href="http://hummingbird.me/anime/' . $anime->hum_id . '" data-toggle="tooltip" title="view on hummingbird" class="met_button">'; ?>
            {{ HTML::image('img/hummingbird-logo.png', 'hummingbird Logo', array("style" => "margin: -8px 0px;")) }}
            </a>
        </div>
    </div>
    <div class="row">
        <?php
        if (empty($anime->youtube_trailer_id)) {
            echo '<div class="span12">
                                <h1 class="met_our_team_name met_color clearfix">Synopsis</h1>
                                <p>' . $anime->description . '</p>
                            </div>';
        } else {
            echo '<div class="span8">
                                <h1 class="met_our_team_name met_color clearfix">Synopsis</h1>
                                <p>' . $anime->description . '</p>
                            </div>';
            echo '<div class="span4" style="float: right; margin-top: 8px;">
                                <iframe style="min-height: 315px;" height="auto" width="100%" src="//www.youtube.com/embed/' . $anime->youtube_trailer_id . '" frameborder="0" allowfullscreen></iframe>
                              </div>';
        }
        ?>
    </div>
    <div class="row">
        <div class="span12">
            <h1 class="met_our_team_name met_color clearfix">Episodes</h1>
            <?php
            $episodes = MasterAnime::getEpisodes($anime->id);
            echo '<ul class="nav nav-tabs nav-stacked">';
            if (empty($episodes)) {
                echo '<li><a href="#">No episodes available for this anime, try again later!</a></li>';
            } else {
                foreach ($episodes as $episode) {
                    echo '<li><a href="' . URL::to('watch/anime/' . $anime->id . '/' . str_replace(array(" ", "/"), "_", $anime->name)) . '/' . $episode . '">' . $episode . '</a></li>';
                }
            }
            echo '</ul>';
            ?>
        </div>
    </div>
</div>
@stop

@endif

