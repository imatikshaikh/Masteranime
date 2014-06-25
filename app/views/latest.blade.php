@extends('layout')

@section('custom-js')
@parent
<script type="text/javascript">
    $(document).ready(function () {
        // init Isotope
        var $container = $('.latest-list').isotope({
            itemSelector: '.item'
        });
        // filter items on button click
        $('.met_filters a').click(function () {
            var filterValue = $(this).attr('data-filter');
            $container.isotope({ filter: filterValue });
        });
    });
</script>
@stop
@section('custom-css')
@parent
<style type="text/css">
    .latest-list li {
        max-width: 1170px;
        width: 100%;
    }

    .latest-list li a {
        height: 44px;
        width: auto;
    }

    .latest-list li img {
        float: left;
        margin: -8px -12px;
        width: 60px;
        height: 60px;
        padding-right: 20px;
    }

    .title {
        float: left;
        margin-top: 13px;
    }

    .time {
        float: right;
        margin-top: 5px;
    }
</style>
@stop
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

                <ul class="nav nav-tabs nav-stacked latest-list">
                    <?php
                    echo Latest::getLatest(array("start" => 0, "end" => 80), false);
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>
@stop