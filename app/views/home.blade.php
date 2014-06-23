@extends('layout')

@section('content')
<div class="row-fluid">
    <div class="span12">
        <div class="clearfix">
            <h3 class="met_title_with_childs pull-left">LATEST ANIME
                <span class="met_subtitle">LATEST EPISODE UPDATES</span>
            </h3>
            <ul class="met_filters met_mainpage_portfolio_filters pull-right">
                <li><a href="#" data-filter=".today">TODAY</a></li>
                <li><a href="#" data-filter=".yesterday">YESTERDAY</a></li>
                <li><a href="#" data-filter=".favorite">FAVORITE</a></li>
                <li><a href="#" data-filter="*" class="met_color3">SHOW ALL</a></li>
            </ul>
        </div>
        <div class="met_recent_works_carousel_wrap">
            <div class="met_recent_works_carousel clearfix scrolled met_mainpage_portfolio">
                <?php
                echo Latest::getLatest();
                ?>
            </div>
        </div>
    </div>
</div>
@stop