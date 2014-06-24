@extends('layout')

@section('custom-js')
@parent
<script type="text/javascript">
    $(document).ready(function() {
        $('input#search').keyup(function(e) {
            e.preventDefault();
            var keyword = $(this).val();
            if (keyword !== ' ' && keyword.length >= 3) {
                $.ajax({
                    type: "POST",
                    url: "anime/search",
                    data: { keyword: keyword },
                    timeout: 2000,
                    success: function(data) {
                        $("#search-results").empty().append(data);
                    }
                });
            } else {
                $("#search-results").empty();
            }
        });
    });
</script>
@stop
@section('content')
<div class="row-fluid " style="margin-bottom: 10px">
    <div class="span12 met_small_block">
        <div class="clearfix">
            <form class="met_contact_form">
                <div class="met_long_container">
                    <input autocomplete="off" id="search" type="text" size="50" class="met_input_text" placeholder="search anime (min. 3 characters)">
                </div>
            </form>
        </div>
    </div>
</div>
<div id="search-results"></div>
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