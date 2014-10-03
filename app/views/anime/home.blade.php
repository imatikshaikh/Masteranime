@extends('layout', array('title' => $anime->name . ' - Watch in HD or SD', 'description' => $anime->name . ' Information, ' .$anime->name. ' Trailer, ' .$anime->name. ' Episodes in HD (720p) or SD (480p)!'))

@section('custom-css')
@parent
<style type="text/css">
    .mod_buttons > a {
        display: block;
        margin-bottom: 5px;
    }

    .mod_buttons > a:hover {
        text-decoration: none;
    }

    .row-fluid {
        margin-bottom: 15px;
    }

    .row-fluid > .row-fluid {
        margin-bottom: 10px;
    }

    .span12 > .title {
        margin: 0;
        font-size: 22px;
    }

    .span7 > .title {
        margin-top: 0;
    }

    p > .sub-title {
        color: #D8D8D8;
        font-weight: bold;
    }

    .cover {
        border-radius: 4px;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
    }

    .screencap {
        max-height: 149px;
        text-align: center;
        overflow: hidden;
    }
</style>
@stop

@section('custom-js')
@parent
<script type="text/javascript">
    $(document).ready(function () {
        $('[user-library=plan_to_watch]').click(function () {
            if (!$(this).attr('disabled')) {
                $.ajax({
                    type: "POST",
                    url: "{{ URL::to('/userlib/plan_to_watch') }}",
                    data: {  anime_id: <?php echo $anime->id ?> }
                }).done(function (data) {
                    $('[user-library=plan_to_watch]').attr('disabled', true);
                    console.log('plan_to_watch action!');
                    $("#notification").prepend(data);
                });
            }
        });
    });
</script>
@stop

@section('content')
<div id="notification"></div>
<div class="row-fluid">
    <div class="span3">
        {{ HTML::image(Anime::getCover($anime), 'Anime cover', array('class' => 'cover')) }}
    </div>
    <div class="span7">
        <h1 class="title">{{ $anime->name }}</h1>

        <div class="tabbable">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#desc" data-toggle="tab">Description</a></li>
                <li class=""><a href="#info" data-toggle="tab">Info</a></li>
                @if (!empty($anime->youtube_trailer_id))
                <li class=""><a href="#trailer" data-toggle="tab">Trailer</a></li>
                @endif
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="desc">
                    <p>{{ $anime->description }}</p>
                </div>
                <div class="tab-pane" id="info">
                    <?php $synonyms = Anime::getSynonyms($anime) ?>
                    @if (count($synonyms) > 1)
                    <p><span class="sub-title">Synonyms:</span> {{ $synonyms }}</p>
                    @endif
                    <p><span class="sub-title">Type:</span> {{ AnimeWrapper::getTypeString($anime->type) }}</p>

                    <p><span class="sub-title">Status:</span> {{ AnimeWrapper::getStatusString($anime->status) }}</p>

                    <p><span class="sub-title">Episodes:</span> {{ $anime->mal_total_eps }}</p>

                    <p><span class="sub-title">Genres:</span> {{ $anime->genres }}</p>
                </div>
                @if (!empty($anime->youtube_trailer_id))
                <div class="tab-pane" id="trailer">
                    <iframe height="290px" width="100%" src="//www.youtube.com/embed/{{ $anime->youtube_trailer_id }}" frameborder="0" allowfullscreen></iframe>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="span2 mod_buttons">
        <a target="_blank" href="http://myanimelist.net/anime/{{ $anime->mal_id }}" type="button" data-toggle="tooltip" title="view on MyAnimeList" class="met_button border-radius-right border-radius-left">{{ HTML::image('img/mal-logo.png', 'myanimelist logo', array("style" => "margin: -8px 0px;")) }}</a>
        <a target="_blank" href="http://hummingbird.me/anime/{{ $anime->hum_id }}" type="button" data-toggle="tooltip" title="view on hummingbird" class="met_button border-radius-right border-radius-left">{{ HTML::image('img/hummingbird-logo.png', 'hummingbird logo', array("style" => "margin: -8px 0px;")) }}</a>
        <a user-library="plan_to_watch" href="#" type="button" data-toggle="tooltip" title="Plan to watch" class="met_button border-radius-right border-radius-left"><i class="icon-plus"></i></a>
    </div>
</div>
<?php
$screencaps = Anime::getScreencaps($anime);
if (!empty($screencaps)) {
    $screencaps = array_slice($screencaps, 0, 4);
}
?>
<div class="row-fluid ads">
    <div class="span12">
        <iframe src="http://g.admedia.com/banner.php?type=graphical-iframe&pid=1738328&size=728x90&page_url=[encoded_page_url]" width="728" height="90" frameborder="0" scrolling="no" allowtransparency="yes"></iframe>
    </div>
</div>
@if (!empty($screencaps))
<div class="row-fluid">
    <div class="row-fluid">
        <div class="span12">
            <h1 class="title">Screencaps</h1>
        </div>
    </div>
    <div class="row-fluid">
        @foreach($screencaps as $screencap)
        <div class="span3 screencap">
            {{ HTML::image($screencap) }}
        </div>
        @endforeach
    </div>
</div>
@endif
<div class="row-fluid">
    <div class="row-fluid">
        <div class="span12">
            <h1 class="title">Episodes</h1>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <?php $episodes = MasterAnime::getEpisodes($anime->id); ?>
            <ul class="nav nav-tabs nav-stacked">
                @if (!empty($episodes))
                @foreach ($episodes as $episode)
                <li>
                    <a href="{{  URL::to('watch/anime/' . $anime->id . '/' . str_replace(array(" ", "/", "?"), "_", $anime->name)) . '/' . $episode }}">
                    <i class="icon-play-circle"></i> {{ $episode }}
                    </a>
                </li>
                @endforeach
                @endif
            </ul>
        </div>
    </div>
</div>
@stop