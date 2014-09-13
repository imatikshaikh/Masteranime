@extends('layout', ["title" => 'Anime list: ' .  e($list->title), "description" => e($list->description)])

@section('custom-js')
@parent
{{ HTML::script('/js/jquery.lazyload.min.js') }}
@stop

@section('content')
<?php $anime_ids = explode(",", $list->anime_ids); ?>
<div class="row-fluid" style="margin-bottom: 20px">
    <div class="span12">
        <h1>{{{ $list->title }}}</h1>

        <p>{{{ $list->description }}}</p>
    </div>
</div>
<hr>
<div class="row-fluid" style="margin-bottom: 20px">
    <div class="span12">
        <div class="row-fluid met_small_block scrolled">
            <div class="span12 scrolled__item">
                <div class="clearfix">
                    <h3 class="met_title_with_childs pull-left">ANIME LIST
                        <span class="met_subtitle">IN TOTAL {{{ count($anime_ids) }}} ANIMES</span></h3>
                </div>
                <div class="row-fluid" style="margin: 0;">
                    @foreach($anime_ids as $anime_id)
                    <?php $anime = Anime::findOrFail($anime_id, array('id', 'name', 'mal_image')); ?>
                    {{ View::make('child.card_anime', array("anime_id" => $anime->id, "anime_name" => $anime->name, "anime_episode" => 0, "anime_img" => $anime->mal_image, "display" => "list")) }}
                    @endforeach
                </div>
                <script type="text/javascript">
                    $("img.lazy").lazyload({
                        effect: "fadeIn",
                        threshold: 100
                    });
                </script>
            </div>
        </div>
    </div>
</div>
<hr>
<div class="row-fluid">
    <div class="span12">
        <h3 class="met_title_with_childs clearfix">DISQUS
            <span class="met_subtitle">COMMENTS FOR THIS LIST</span>
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
            <a href="http://disqus.com/?ref_noscript">comments
                powered by Disqus.</a></noscript>
        <a href="http://disqus.com" class="dsq-brlink">comments powered by <span
                class="logo-disqus">Disqus</span></a>

    </div>
</div>
@stop