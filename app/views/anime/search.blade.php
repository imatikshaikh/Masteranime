@extends('layout', ["footer" => false])

@section('custom-css')
@parent
<style type="text/css">
    .row-fluid {
        margin-bottom: 20px;;
    }

    .label {
        margin: 0 3px 0 3px;
        font-size: 12px;
    }

    .anime_display_wide {
        background-color: #212121;
        border-radius: 4px;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        max-height: 180px;
        margin-bottom: 15px;
    }

    .anime_display_wide > a > div > img {
        float: left;
        height: 180px;
        width: auto;
        margin-right: 10px;
        webkit-border-top-left-radius: 4px;
        -moz-border-radius-topleft: 4px;
        border-top-left-radius: 4px;
        -webkit-border-bottom-left-radius: 4px;
        -moz-border-radius-bottomleft: 4px;
        border-bottom-left-radius: 4px;
    }

    .anime_display_wide > a > div > h1 {
        color: #D8D8D8;
        margin: 3px 0 5px 0;
        font-size: 26px;
    }

    .anime_display_wide > a > div > p {

        font-size: 12px;
        max-height: 140px;
        word-wrap: normal;
        overflow-y: hidden;
        margin: 0 5px 8px;
    }

    .anime_display_wide > a {
        color: #595959;
    }

    .anime_display_wide > a:hover {
        color: #595959;
        text-decoration: none;
    }

    .anime_display_wide > a > div > p:hover {
        overflow-y: scroll;
    }
</style>
@stop

@section('content')
<div class="row-fluid">
    <div class="span12">
        <div class="row-fluid">
            <div class="span12">
                <div class="clearfix">
                    <h3 class="met_title_with_childs pull-left">
                        SEARCH BOX<span class="met_subtitle">SEARCH BY ANIME NAME/SYNONYMS</span>
                    </h3>
                </div>

                <form class="search_form met_contact_form" method="get" action="{{ URL::to('/anime/search') }}">
                    <input class="met_input_text" id="search" name="query" autocomplete="off" type="text" maxlength="50" placeholder="anime name"><input class="met_button" type="submit" value="Search">
                </form>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <div class="clearfix">
                    <h3 class="met_title_with_childs pull-left">
                        ALL GENRES<span class="met_subtitle">SEARCH BY GENRE</span>
                    </h3>
                </div>

                <div id="genres">
                    @foreach(AnimeWrapper::$genres as $genre)
                    <a href="{{ URL::to('/anime/search/' . $genre) }}"><span class="label label-inverse">{{$genre}}</span></a>
                    @endforeach
                </div>
            </div>
        </div>
        @if (isset($results))
        @if (count($results)<= 0)
        <h1>We did not find any search results! (Showing all anime)</h1>
        <hr>
        {{ View::make('child.all_anime') }}
        @else
        <h1>Search results: {{{ $search or ""}}}</h1>
        <hr>
        @foreach($results as $result)
        <div class="row-fluid anime_display_wide">
            <a href="{{ URL::to('anime/' . $result->id . '/' . str_replace(array(" ", "/", "?"), "_", $result->name)) }}">
            <div class="span12">
                {{ HTML::image(Anime::getCover($result)) }}
                <h1>
                    {{ $result->name }}
                </h1>

                <p>{{ $result->description }}</p>
            </div>
            </a>
        </div>
        @endforeach
        <div class="row-fluid">
            <div class="span12">
                {{ $results->links(); }}
            </div>
        </div>
        @endif
        @endif
    </div>
</div>
@stop