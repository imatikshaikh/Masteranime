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
                </div>
                <?php
                echo Latest::getLatest(array("start" => 0, "end" => 80), false);
                ?>
            </div>
        </div>
    </div>
</div>
@stop