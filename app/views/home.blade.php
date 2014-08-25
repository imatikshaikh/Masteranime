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
        <h3 class="met_title_with_childs pull-left">RECENT ANIME
            <span class="met_subtitle">ANIME RECENTLY RELEASED</span>
        </h3>

        <div class="clearfix"></div>
        <div class="row-fluid">
            <?php
            $latest_eps = Latest::getLatestRows(["paginate" => 12]);
            if (!empty($latest_eps) && count($latest_eps) > 0) {
                foreach ($latest_eps as $latest_ep) {
                    echo View::make('child.card_anime', array(
                        "anime_id" => $latest_ep->anime_id,
                        "anime_name" => $latest_ep->name,
                        "anime_episode" => $latest_ep->episode,
                        "anime_img" => $latest_ep->img,
                        "time" => $latest_ep->created_at
                    ));
                }
            }
            ?>
            <div class="row-fluid">
                <div class="span12">
                    {{ $latest_eps->links(); }}
                </div>
            </div>
        </div>
    </div>
</div>
<hr>
<div class="row-fluid" style="margin-bottom: 20px;">
    <div class="span12">
        <h3 class="met_title_with_childs pull-left">ANIME OF THE WEEK
            <span class="met_subtitle">NANA</span>
        </h3>
    </div>
    <div class="row-fluid">
        <div class="span6 text-center">
            {{ HTML::image('img/animeoftheweek/nana.png', 'anime of the week: Nana',
            ["class" =>
            "met_br_tl met_br_tr met_br_bl met_br_br"]) }}
        </div>
        <div class="span6">
            <a href="{{ URL::to("/anime/272/Nana") }}"><h3 class="met_big_title">Nana</h3>
            </a>

            <p>

            <p>Komatsu Nana moves to Tokyo, following after her boyfriend Shouji to gain a life she has always dreamed
                of. On a train bound for Tokyo, she meets Osaki Nana, the vocalist for the punk rock band "Blast", also
                moving to Tokyo to achieve her dreams of becoming a professional. Sharing the same name, the two of them
                perhaps through a twist of fate, ended up becoming flat mates in Tokyo, where together they support each
                other through each of their love lives and careers.</p>
                <span class="met_color"><a href="{{ URL::to("/anime/272/Nana") }}">All episodes</a>
                    <a
                        class="pull-right"
                        href="http://myanimelist.net/anime/877/Nana/reviews">Nana
                        reviews</a></span>
            <br><br>
            </p>
        </div>
    </div>
</div>
<hr>
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