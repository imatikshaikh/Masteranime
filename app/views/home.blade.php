@extends('layout')

@section('custom-css')
@parent
<style type="text/css">
    .row-fluid {
        margin-bottom: 20px;
    }
</style>
@stop

@section('content')
<div class="row-fluid" style="margin-bottom: 10px;">
    <div class="span12">
        <form class="search_form met_contact_form" method="get" action="{{ URL::to('/anime/search') }}">
            <input class="met_input_text" id="search" name="query" autocomplete="off" type="text" maxlength="50" placeholder="anime name/synonyms.."><input class="met_button" type="submit" value="Search">
        </form>
    </div>
</div>
<div class="row-fluid">
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
                        "time" => $latest_ep->created_at,
                        "display" => "latest"
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
<div class="row-fluid">
    <div class="span12">
        <h3 class="met_title_with_childs pull-left">ANIME OF THE WEEK
            <span class="met_subtitle">High School DxD</span>
        </h3>
    </div>
    <div class="row-fluid">
        <div class="span6 text-center">
            {{ HTML::image('img/animeoftheweek/highschool_dxd.jpg', 'anime of the week: High School DxD',
            ["class" =>
            "met_br_tl met_br_tr met_br_bl met_br_br"]) }}
        </div>
        <div class="span6">
            <a href="{{ URL::to("/anime/223/High_School_DxD") }}"><h3 class="met_big_title">High School DxD</h3></a>
            <p>Issei Hyodo is your average perverted high school student whose one wish in life is to have his own harem, but he's got to be one of the unluckiest guys around. He goes on his first date with a girl only to get brutally attacked and killed when it turns out the girl is really a vicious fallen angel. To top it all off, he's later reincarnated as a devil by his gorgeous senpai who tells him that she is also a devil and now his master! One thing's for sure, his peaceful days are over. In a battle between devils and angels, who will win? </p>
                <span class="met_color">
                    <a href="{{ URL::to("/anime/223/High_School_DxD") }}">All episodes</a>
                    <a class="pull-right" href="http://myanimelist.net/anime/11617/High_School_DxD/reviews">High School DxD reviews</a>
                </span>
            <br><br>
            </p>
        </div>
    </div>
</div>
<hr>
<div class="row-fluid">
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
        <noscript>Please enable JavaScript to view the
            <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
        <a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
    </div>
</div>
@stop