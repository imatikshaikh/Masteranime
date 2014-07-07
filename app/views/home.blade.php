@extends('layout')

@section('custom-js')
@parent
<script type="text/javascript">
    $(document).ready(function () {
        $('input#search').keyup(function (e) {
            e.preventDefault();
            var keyword = $(this).val();
            if (keyword !== ' ' && keyword.length >= 3) {
                $.ajax({
                    type: "POST",
                    url: "anime/search",
                    data: { keyword: keyword },
                    timeout: 2000,
                    success: function (data) {
                        $("#search-results").empty().append(data);
                    }
                });
            } else {
                $("#search-results").empty();
            }
        });
        $('ul#met_filters_list a').on('click', function (e) {
            e.preventDefault();
            var type = $(this).find('input[name="type"]').val();
            if (type === "gallery" || type === "list") {
                $.ajax({
                    type: "POST",
                    url: "anime/recent",
                    data: { type: type },
                    timeout: 2000,
                    success: function (data) {
                        $("#recent-released").empty().append(data);
                    }
                });
            }
        });
    });
</script>
@stop
@section('content')
<div class="row-fluid" style="margin-bottom: 15px">
    <div class="span12 met_small_block">
        <div class="clearfix">
            <form class="met_contact_form">
                <div class="met_long_container">
                    <input autocomplete="off" id="search" type="text" size="50" class="met_input_text"
                           placeholder="search anime (min. 3 characters)">
                </div>
            </form>
        </div>
    </div>
</div>
<div id="search-results"></div>
<div class="row-fluid" style="margin-bottom: 20px;">
    <div class="span12">
        <div class="clearfix">
            <h3 class="met_title_with_childs pull-left">LATEST ANIME
                <span class="met_subtitle">LATEST EPISODE UPDATES</span>
            </h3>
            <ul id="met_filters_list" class="met_filters met_mainpage_portfolio_filters">
                <li><a href="#"><input hidden="true" name="type" value="gallery"><span class="icon-th"></span></a></li>
                <li><a href="#"><input hidden="true" name="type" value="list"><span class="icon-th-list"></span></a>
                </li>
            </ul>
            <ul class="met_filters met_mainpage_portfolio_filters pull-right">
                <li><a href="#" data-filter=".favorite">FAVORITE</a></li>
                <li><a href="#" data-filter="*" class="met_color3">SHOW ALL</a></li>
            </ul>
        </div>
        <div id="recent-released">
            <?php
            $value = Cookie::get(MasterAnime::$cookie_recent_layout);
            if (isset($value) && $value != 'list') {
                echo Latest::getLatest(array("start" => 0, "end" => 12), false);
            } else {
                echo Latest::getLatest();
            }
            ?>
        </div>
    </div>
</div>
<hr>
<div class="row-fluid" style="margin-bottom: 20px;">
    <div class="span12">
        <h3 class="met_title_with_childs pull-left">ANIME OF THE WEEK</h3>
    </div>
    <div class="row-fluid">
        <div class="span6">
            {{ HTML::image('img/animeoftheweek/hunter_x_hunter.png', 'anime of the week: hunter x hunter', ["class" =>
            "met_br_tl met_br_tr met_br_bl met_br_br"]) }}
        </div>
        <div class="span6">
            <a href="{{ URL::to("/anime/3/Hunter_x_Hunter_(2011)") }}"><h3 class="met_big_title">Hunter X Hunter
                (2011)</h3>
            </a>

            <p>A new adaptation of the the manga series by Togashi Yoshihiro.

                A Hunter is one who travels the world doing all sorts of dangerous tasks. From capturing criminals to
                searching deep within uncharted lands for any lost treasures. Gon is a young boy whose father
                disappeared long ago, being a Hunter. He believes if he could also follow his father's path, he could
                one day reunite with him.

                After becoming 12, Gon leaves his home and takes on the task of entering the Hunter exam, notorious for
                its low success rate and high probability of death to become an official Hunter. He befriends the
                revenge-driven Kurapika, the doctor-to-be Leorio and the rebellious ex-assassin Killua in the exam, with
                their friendship prevailing throughout the many trials and threats they come upon taking on the
                dangerous career of a Hunter.
                <br><br>
                <span class="met_color"><a href="{{ URL::to("/anime/3/Hunter_x_Hunter_(2011)") }}">All episodes</a> <a
                        class="pull-right"
                        href="http://myanimelist.net/anime/11061/Hunter_x_Hunter_(2011)/reviews">Hunter X Hunter (2011)
                        reviews</a></span>
                <br><br>
            </p>
        </div>
    </div>
</div>
<hr>
<div class="row-fluid hidden-phone" style="margin-bottom: 20px;">
    <div class="span12">
        <h3 class="met_title_with_childs pull-left">RECOMMENDED ANIME
            <span class="met_subtitle">POPULAR/ONGOING ANIME</span>
        </h3>

        <div class="row-fluid">
            <div class="span12">
                <div class="row-fluid">
                    {{ MasterAnime::printPopularAnime(); }}
                </div>
            </div>
        </div>
    </div>
</div>
<hr class="hidden-phone">
<div class="row-fluid scrolled">
    <div class="span12">
        <h3 class="met_title_with_childs clearfix">DISQUS
            <span class="met_subtitle">TALK ABOUT THE SERIES AND EPISODES</span>
        </h3>

        <div style="margin: 1em;" id="disqus_thread"></div>
        <script type="text/javascript">
            /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
            var disqus_shortname = 'masteranime'; // required: replace example with your forum shortname

            /* * * DON'T EDIT BELOW THIS LINE * * */
            (function () {
                var dsq = document.createElement('script');
                dsq.type = 'text/javascript';
                dsq.async = true;
                dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
                (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
            })();
        </script>
        <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments
                powered by Disqus.</a></noscript>
        <a href="http://disqus.com" class="dsq-brlink">comments powered by <span
                class="logo-disqus">Disqus</span></a>
    </div>
</div>
@stop