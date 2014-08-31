<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Masterani - {{ $title or 'Watch anime in HD'}}</title>
    <link rel="icon" type="image/ico" href="{{ URL::to('favicon.ico') }}"/>
    <meta name="description" content=<?php if (isset($description)) {
        echo '"' . $description . '"';
    } else {
        echo '"Masterani allows you to watch anime in HD (720p) and SD (480p)! Features XBMC, Plex and auto-updating MyAnimeList or Hummingbird library!"';
    } ?>>
    <!-- for Facebook -->
    <meta property="og:image" content={{
    $social_image or asset('img/masteranime_logo.png') }}/>
    <meta property="og:title" content=<?php if (isset($title)) {
        echo '"' . $title . '"';
    } else {
        '"Masterani - Watch anime in HD"';
    } ?>/>
    <meta property="og:description" content=<?php if (isset($description)) {
        echo '"' . $description . '"';
    } else {
        echo '"Masterani allows you to watch anime in HD and SD! Features XBMC, Plex & auto-updating MyAnimeList or Hummingbird library!"';
    } ?>/>
    <meta property="og:url" content=<?php echo '"' . URL::current() . '"' ?>/>
    <meta property="og:site_name" content="Masterani"/>
    <meta property="og:type" content="website"/>

    <!-- for Twitter -->
    <meta name="twitter:card" content="summary"/>
    <meta name="twitter:title" content=<?php if (isset($title)) {
        echo '"' . $title . '"';
    } else {
        '"Masterani - Watch anime in HD"';
    } ?>/>
    <meta name="twitter:description" content=<?php if (isset($description)) {
        echo '"' . $description . '"';
    } else {
        echo '"Masterani allows you to watch anime in HD and SD! Features XBMC, Plex & auto-updating MyAnimeList or Hummingbird library!"';
    } ?>/>
    <meta name="twitter:image" content={{
    $social_image or asset('img/masteranime_logo.png') }} />

    <!-- CSS -->
    @section('custom-css')
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300|Roboto+Condensed|Roboto' rel='stylesheet'
          type='text/css'>
    {{ HTML::style('css/bootstrap.css') }}
    {{ HTML::style('css/font-awesome.min.css') }}
    <!--[if IE 7]>
    {{ HTML::style('css/font-awesome-ie7.min.css') }}
    <![endif]-->
    {{ HTML::style('css/style.css') }}
    {{ HTML::style('css/animate.css') }}
    {{ HTML::style('css/prettyPhoto.css') }}
    {{ HTML::style('css/photoswipe.css') }}
    {{ HTML::style('css/dl-menu.css') }}
    {{ HTML::style('css/custom.css') }}
    <!--[if lte IE 8]>
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:300"/>
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto"/>
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto+Condensed"/>
    {{ HTML::style('css/lte-ie8.css') }}
    <![endif]-->
    @show


    <!-- Scripts-->
    @section('custom-js')
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    {{ HTML::script('js/modernizr.custom.65274.js') }}
    {{ HTML::script('js/jquery.migrate.js') }}
    {{ HTML::script('js/jquery.onecarousel.min.js') }}
    <!--[if (gte IE 6)&(lte IE 8)]> {{ HTML::script('js/selectivizr-min.js') }}<![endif]-->
    {{ HTML::script('js/min/bootstrap.js') }}
    {{ HTML::script('js/caroufredsel.js') }}
    {{ HTML::script('js/masonry2108.js') }}
    {{ HTML::script('js/mobile_detector.js') }}
    {{ HTML::script('js/jquery.prettyPhoto.js') }}
    {{ HTML::script('js/klass.min.js') }}
    {{ HTML::script('js/photoswipe.js') }}
    {{ HTML::script('js/reveal.js') }}
    {{ HTML::script('js/fullscreenr.js') }}
    {{ HTML::script('js/jquery.easyticker.js') }}
    {{ HTML::script('js/isotope.js') }}
    <!--[if lte IE 10]>{{ HTML::script('js/jquery.color.js') }}<![endif]-->
    {{ HTML::script('js/min/jquery.dlmenu.js') }}
    {{ HTML::script('js/custom.js') }}
    <script type="text/javascript">
        $(document).ready(function () {
            $("[data-toggle=popover]").popover();
            $("[data-toggle=tooltip]").tooltip({ placement: 'top'});
            $("[data-toggle=tooltip-bottom]").tooltip({ placement: 'bottom'});
            $('#announcement button').on('click', function () {
                $.ajax({
                    type: "GET",
                    url: "{{ URL::to('/disable/announcement'); }}",
                    timeout: 10000
                });
            });
        });
    </script>
    @show
</head>
<body>
<div class="met_page_wrapper met_fixed_header clearfix">
    <header class="clearfix">
        <div class="met_header_head">
            <div class="met_content">
                <div class="row-fluid">
                    <div class="span12">
                        <a target="_blank" href="https://www.facebook.com/masteranidotme" data-toggle="tooltip-bottom"
                           title="Like us" class="pull-left met_color_transition met_header_head_link"><i
                                class="icon-facebook"></i></a>
                        <a target="_blank" href="https://twitter.com/masteranidotme" data-toggle="tooltip-bottom"
                           title="Follow us" class="pull-left met_color_transition met_header_head_link"><i
                                class="icon-twitter"></i></a>
                        <a href="{{ URL::to('account/myanime') }}" data-toggle="tooltip-bottom" title="MyAnime"
                           class="pull-left met_color_transition met_header_head_link"><i
                                style="font-size: 18px; margin-left: -0.1em;" class="icon-heart"></i></a>

                        <div id="dl-menu" class="dl-menuwrapper">
                            <button>Open Menu</button>
                            <ul class="dl-menu">
                                {{ HTML::menu_link (array( array("route" => "/", "text" => "HOME") ) ) }}
                                {{ HTML::menu_link (array( array("route" => "/animehd", "text" => "XBMC / Plex"))) }}
                                {{ HTML::menu_link(array(array("route" => "anime", "text" => "ANIME"), array("route" =>
                                "anime",
                                "text" => "ANIME LIST"), array("route" =>
                                "anime/latest", "text" => "LATEST ANIME"), array("route" => "anime/chart", "text" =>
                                "ANIME
                                CHART")), true) }}
                                <?php
                                if (Sentry::check()) {
                                    $user = Sentry::getUser();
                                    echo HTML::menu_link(array(array("route" => 'account', "text" => 'account - ' . $user->username), array("route" => 'account', "text" => 'settings'), array("route" => 'account/myanime', "text" => 'myanime'), array("route" => 'account/logout', "text" => 'LOG OUT')), true);
                                } else {
                                    echo HTML::menu_link(array(array("route" => 'account', "text" => 'SIGN IN'), array("route" => 'account', "text" => 'SIGN IN'), array("route" => 'account/register', "text" => 'SIGN UP')), true);
                                }
                                ?>
                            </ul>
                        </div>
                        <!-- /dl-menuwrapper -->
                    </div>
                </div>
            </div>
        </div>
        <div class="met_content">
            <div class="row-fluid">
                <div class="span12">
                    <a href="{{ URL::to('/') }}" class="met_logo_container pull-left scrolled">{{
                        HTML::image('img/masteranime_logo.png', 'masterani logo') }}</a>

                    <div class="met_header_search_box"></div>
                    <ul class="met_main_menu pull-right scrolled">
                        {{ HTML::menu_link (array( array("route" => "/", "text" => "HOME") ) ) }}
                        {{ HTML::menu_link (array( array("route" => "/animehd", "text" => "XBMC / Plex"))) }}
                        {{ HTML::menu_link(array(array("route" => "anime", "text" => "ANIME"), array("route" => "anime",
                        "text" => "ANIME LIST"), array("route" =>
                        "anime/latest", "text" => "LATEST ANIME"), array("route" => "anime/chart", "text" => "ANIME
                        CHART"))) }}
                        <?php
                        if (Sentry::check()) {
                            $user = Sentry::getUser();
                            echo HTML::menu_link(array(array("route" => 'account', "text" => 'account - ' . $user->username), array("route" => 'account', "text" => 'settings'), array("route" => 'account/myanime', "text" => 'myanime'), array("route" => 'account/logout', "text" => 'LOG OUT')));
                        } else {
                            echo HTML::menu_link(array(array("route" => 'account', "text" => 'SIGN IN'), array("route" => 'account/register', "text" => 'SIGN UP')));
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    @yield('anime-header')
    <section class="met_content clearfix" style="margin-top: 30px;">
        @if (empty(Cookie::get('masterani_announcement')))
        <div id="announcement" class="alert alert-info alert-dismissable">
            <button type="button" class="close" data-dismiss="alert"
                    aria-hidden="true">&times;</button>
            <strong>Chance to win Riot kayle skin & Riot ward skin! (League of Legends)</strong><br> Follow
            our <a href="https://twitter.com/masteranidotme"
                   style="color: #3a87ad; text-decoration: underline;">twitter</a> and get a chance to win! 2 winners
            will be announced at 5/9/2014 randomly picked from the followers.
        </div>
        @endif
        @yield('content')
    </section>
</div>

@if ((isset($footer) && $footer) || !isset($footer))
<footer class="clearfix visible-desktop">
    <div class="met_footer_footer clearfix">
        <div class="met_content_footer">
            <a href="{{ URL::to('/') }}" class="pull-left">{{ HTML::image('img/masteranime_logo.png', 'masteranime
                logo') }}</a>

            <p class="pull-left">© 2014 MASTERANI. All Rights Reserved.</p>
            <ul class="met_footer_menu pull-right">
                {{ HTML::menu_link (array( array("route" => "/", "text" => "HOME") ) ) }}
                {{ HTML::menu_link(array(array("route" => "/anime", "text" => "ANIME LIST"))) }}
                {{ HTML::menu_link(array(array("route" => "/anime/chart", "text" => "ANIME CHART"))) }}
            </ul>
        </div>
    </div>
</footer>
@endif
<div id="back-to-top" class="off back-to-top-off"></div>
</body>
</html>
