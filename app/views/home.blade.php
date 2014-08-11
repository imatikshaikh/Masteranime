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
            {{ HTML::image('img/animeoftheweek/Fullmetal_Alchemist_Brotherhood.png', 'anime of the week: Fullmetal
            Alchemist: Brotherhood',
            ["class" =>
            "met_br_tl met_br_tr met_br_bl met_br_br"]) }}
        </div>
        <div class="span6">
            <a href="{{ URL::to("/anime/226/Fullmetal_Alchemist:_Brotherhood") }}"><h3 class="met_big_title">Fullmetal
                Alchemist: Brotherhood</h3>
            </a>

            <p>

            <p>In this world there exist alchemists, people who study and perform the art of alchemical transmutation—to
                manipulate objects and transform one object into another. They are bounded by the basic law of alchemy:
                in order to gain something you have to sacrifice something of the same value.
            </p>

            <p>
                The main character is the famous alchemist Edward Elric—also known as the Fullmetal Alchemist—who almost
                lost his little brother, Alphonse, in an alchemical accident. Edward managed to attach his brother's
                soul to a large suit of armor. While he did manage to save his brother's life, he paid the terrible
                price of his limbs.
            </p>

            <p>
                To get back what they've lost, the brothers embark on a journey to find the Philosopher's Stone that is
                said to amplify the powers of an alchemist enormously. However on the way, they start uncovering a
                conspiracy that could endanger the entire nation, and they realize the misfortunes brought upon by the
                Philosopher's Stone.
            </p>
                <span class="met_color"><a href="{{ URL::to("/anime/226/Fullmetal_Alchemist:_Brotherhood") }}">All episodes</a>
                    <a
                        class="pull-right"
                        href="http://myanimelist.net/anime/5114/Fullmetal_Alchemist:_Brotherhood/reviews">Steins;Gate
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
            var disqus_shortname = 'masterani'; // required: replace example with your forum shortname

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