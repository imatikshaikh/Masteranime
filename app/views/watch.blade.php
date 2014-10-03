@extends('layout')

@section('custom-js')
@parent
<script type="text/javascript">
    $(document).ready(function () {
        $(".ads #close-ads").click(function (e) {
            e.preventDefault();
            $(".ads").hide();
        });
        $("[data-toggle=mirror]").click(function (e) {
            e.preventDefault();
            var id = $(this).find("input[name='id']").val();
            $.ajax({
                type: "POST",
                url: "../../../../watch/anime/mirror",
                data: { id: id },
                timeout: 10000,
                success: function (data) {
                    $("#video").empty().append(data);
                },
                error: function () {
                    $("#video").empty().append('Failed loading video, refresh the page.');
                }
            });
        });
        $("#favorite-anime a").click(function (e) {
            e.preventDefault();
            var user_id = $(this).find("input[name='user_id']").val();
            var anime_id = $(this).find("input[name='anime_id']").val();
            $.ajax({
                type: "POST",
                url: "../../../../userlib/favorite",
                data: { user_id: user_id, anime_id: anime_id },
                timeout: 10000,
                success: function (data) {
                    $("#favorite-anime").empty().append(data);
                }
            });
        });
    });
</script>
@stop

@section('custom-css')
@parent
<style type="text/css">
    #controls-watch {
        margin-top: 10px;
    }

    #controls-watch a:hover {
        text-decoration: none;
    }

    #controls-watch a {
        margin-right: 20px;
    }

    .alert-info a {
        color: #3a87ad;
        text-decoration: underline;
    }
</style>
@stop

@section('content')
<div class="row-fluid" style="margin-bottom: 20px;">
    <div class="span12">
        {{ $update_msg or '' }}
        @if (Sentry::check())
        <?php $user = Sentry::getUser(); ?>
        @endif
        @if (isset($anime) && isset($mirrors) && isset($episode))
        <div class="row-fluid">
            <div class="span9">
                <h3 class="met_big_title">{{{ $anime->name }}} - episode {{{ $episode }}}</h3>

                <div id="video">
                    <iframe frameborder="0" scrolling="no" width="100%" height="510px" src="{{  $mirrors[0]->src }}" allowfullscreen></iframe>
                </div>
                <div class="row-fluid">
                    <div class="span12" id="controls-watch">
                        <div id="favorite-anime" class="pull-left">
                            @if (!empty($user))
                            @if (UserLibrary::getFavorite($anime->id, $user->id))
                            <a data-toggle="tooltip" title="remove from favorites"><input type="hidden" name="user_id" value="{{{ $user->id }}}"><input type="hidden" name="anime_id" value="{{{ $anime->id }}}"><span class="icon-heart icon-large met_gray_icon"></span></a>
                            @else
                            <a data-toggle="tooltip" title="add to favorites"><input type="hidden" name="user_id" value="{{{ $user->id }}}"><input type="hidden" name="anime_id" value="{{{ $anime->id }}}"><span class="icon-heart icon-large"></span></a>
                            @endif
                            @else
                            <a data-toggle="tooltip" title="Sign in to favorite anime"><span class="icon-heart icon-large"></span></a>
                            @endif
                        </div>
                        <div class="pull-right">
                            <?php
                            $prev = MasterAnime::getPrevEpisode($anime->id, $episode);
                            $next = MasterAnime::getNextEpisode($anime->id, $episode);
                            ?>
                            @if (!empty($prev))
                            <a href="{{{ URL::to('/watch/anime/' . $anime->id . '/' . str_replace(array(" ", "/", "?"), '_', $anime->name) . '/' . $prev) }}}" data-toggle="tooltip" title="previous episode"><span class="icon-arrow-left icon-large"></span></a>
                            @endif
                            <a href="{{{ URL::to('/anime/' . $anime->id . '/' . str_replace(array(" ", "/", "?"), '_', $anime->name)) }}}" data-toggle="tooltip" title="episode list"><span><i class="icon-th icon-large"></i></span></a>
                            @if (!empty($next))
                            <a href="{{{ URL::to('/watch/anime/' . $anime->id . '/' . str_replace(array(" ", "/", "?"), '_', $anime->name) . '/' . $next) }}}" data-toggle="tooltip" title="next episode"><span class="icon-arrow-right icon-large"></span></a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="span3">
                <div class="row-fluid">
                    <h3 class="met_big_title">Mirrors</h3>

                    <div class="span12">
                        <ul class="nav nav-tabs nav-stacked">
                            @foreach($mirrors as $mirror)
                            <li>
                                <a href="#" data-toggle="mirror"><input type="hidden" name="id" value="{{{ $mirror->id }}}">
                                    {{{ $mirror->host }}}
                                    <div class="pull-right">
                                        @if ($mirror->quality <= 480)
                                        <span class="tag-blue">SD</span>
                                        @else
                                        <span class="tag-red">HD</span>
                                        @endif
                                    </div>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                        <div class="ads">
                            <iframe src="http://g.admedia.com/banner.php?type=graphical-iframe&pid=3511128&size=300x250&page_url=[encoded_page_url]" width="300" height="250" frameborder="0" scrolling="no" allowtransparency="yes"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <?php Redirect::to('anime') ?>
        @endif
    </div>
</div>
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
<?php
$end = 1;
if (empty($next) && $anime->status != 1) {
    $end = 2;
}
echo '<script type="text/javascript">
        $(document).ready(function() {
            setTimeout(function () {
                $.ajax({
                    type: "POST",
                    url: "../../../../userlib/watched",
                    data: {  anime_id: ' . $anime->id . ', episode_id: ' . $episode . ', completed: ' . $end . ' },
                    timeout: 10000
                }).done(function(data) {
                    $("#video").append(data);
                });
            }, 420000);
        });
    </script>';
?>
@stop