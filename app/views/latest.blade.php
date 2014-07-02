@extends('layout')

@section('content')
<div class="row-fluid">
    <div class="span12">
        <div class="row-fluid met_small_block scrolled">
            <div class="span12 scrolled__item">
                <div class="clearfix">
                    <h3 class="met_title_with_childs pull-left">LATEST ANIME
                        <span class="met_subtitle">LATEST EPISODE UPDATES</span>
                    </h3>
                    <ul class="met_filters pull-right">
                        <li><a href="#" data-filter=".favorite">FAVORITE</a></li>
                        <li><a href="#" data-filter="*" class="met_color3">SHOW ALL</a></li>
                    </ul>
                </div>
                <?php
                echo Latest::getLatest(array("start" => 0, "end" => 80), false);
                ?>
            </div>
        </div>
    </div>
</div>
@stop