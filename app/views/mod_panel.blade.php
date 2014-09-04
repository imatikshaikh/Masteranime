@extends('layout', ['footer' => false])

@section('custom-css')
@parent
<style type="text/css">
    .mod-buttons > a {
        display: block;
        margin-bottom: 20px;
    }

    .mod-buttons > a:hover {
        text-decoration: none;
    }

    form > input, select, label {
        margin-right: 10px;
        margin-bottom: 10px;
    }
</style>
@stop
@section('content')
@if (isset($update_msg))
<div class="row-fluid">
    <div class="span12">
        {{ $update_msg }}
    </div>
</div>
@endif
<div class="row-fluid">
    <div class="span10">
        <div class="clearfix">
            <h3 class="met_title_with_childs">
                ANIME MANAGE PANEL<span class="met_subtitle">MODERATION PANEL TO EDIT/ADD ANIME</span>
            </h3>
            <hr>
            {{ Form::open(array('action' => 'manage_single_mirror', 'class' => 'form-inline')) }}
            <label>add mirror</label>
            {{ Form::text('anime_id', $value = null, array('class' => 'input-small', 'required' => '', 'placeholder' => 'anime id')) }}
            {{ Form::text('episode', $value = null, array('class' => 'input-small', 'required' => '', 'placeholder' => 'episode' )) }}
            {{ Form::text('src', $value = null, array('class' => 'input-large', 'required' => '', 'placeholder' => 'link to src')) }}
            {{ Form::select('host', array('MP4Upload' => 'MP4Upload', 'Arkvid' => 'Arkvid'), 'MP4Upload', array('class' => 'input-small')) }}
            {{ Form::select('quality', array('720' => '720p', '480' => '480p', '1080' => '1080p'), '720', array('class' => 'input-small')) }}
            {{ Form::submit('update', array('class' => 'btn btn-success btn-lg')) }}
            {{ Form::close() }}
            <hr>
            {{ Form::open(array('action' => 'manage_single_anime', 'class' => 'form-inline')) }}
            <label>add/update new anime</label>
            {{ Form::text('mal_id', $value = null, array('class' => 'input-small', 'required' => '', 'placeholder' => 'mal id')) }}
            {{ Form::text('keyword', $value = null, array('class' => 'input-large', 'required' => '', 'placeholder' => 'anime keyword' )) }}
            {{ Form::text('hum_id', $value = null, array('class' => 'input-large', 'placeholder' => 'hummingbird id')) }}
            {{ Form::submit('update', array('class' => 'btn btn-success btn-lg')) }}
            {{ Form::close() }}
            <hr>
            {{ Form::open(array('action' => 'add_scrapeurl', 'class' => 'form-inline')) }}
            <label>add scraper suffix</label>
            {{ Form::text('anime_id', $value = null, array('class' => 'input-small', 'placeholder' => 'anime id')) }}
            {{ Form::text('suffix_animerush', $value = null, array('class' => 'input-large', 'placeholder' => 'suffix ar')) }}
            {{ Form::text('suffix_rawranime', $value = null, array('class' => 'input-large', 'placeholder' => 'suffix ra')) }}
            {{ Form::text('othername', $value = null, array('class' => 'input-small', 'placeholder' => 'othername')) }}
            {{ Form::submit('update', array('class' => 'btn btn-success btn-lg')) }}
            {{ Form::close() }}
        </div>
    </div>
    <div class="span2">
        <div class="clearfix">
            <h3 class="met_title_with_childs">
                ACTIONS
            </h3>
            <hr>
            <div class="mod-buttons">
                <a href="{{{ URL::to('anime/scraper/all') }}}" type="button" data-toggle="tooltip" title="scrape anime with no eps" class="met_button border-radius-right border-radius-left"><span class="icon-download-alt"></span></a>
                <a href="{{{ URL::to('/anime/manage/ongoing') }}}" type="button" data-toggle="tooltip" title="update ongoing anime" class="met_button border-radius-right border-radius-left"><span class="icon-repeat"></span></a>
            </div>
        </div>
    </div>
</div>
@stop