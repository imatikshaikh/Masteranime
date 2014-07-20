@extends('layout', ['title' => 'AnimeHD - XBMX add-on & Plex plugin', 'description' => 'The XBMC add-on / Plex plugin with all ongoing anime & over 400+ completed anime available in SD and HD', 'footer' => false])

@section('content')
<div class="row-fluid">
    <div class="span12">
        <div class="met_page_title">
            <h1>AnimeHD</h1>

            <p>The XBMC add-on / Plex plugin with all ongoing anime & over 400+ completed anime available in SD and
                HD.</p>
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="row-fluid">
            <div class="span6">
                <div class="clearfix">
                    <h3 class="met_title_with_childs pull-left">Plex Plugin : AnimeHD
                        <span class="met_subtitle">FREQUENTLY ASKED QUESTIONS ABOUT PLEX</span>
                    </h3>
                </div>
                <div class="met_kabubu_accordion">
                    <div class="met_kabubu_accordion_item met_kabubu_accordion_item_active">
                        <a href="#" class="met_kabubu_accordion_sign"><i class="icon-minus met_color"></i></a>

                        <div class="met_kabubu_accordion_detail">
                            <a href="#" class="met_kabubu_item_title">How to get AnimeHD on Plex?</a>

                            <p class="met_kabubu_accordion_descr" style="display: block;">AnimeHD is available at <a
                                    href="https://github.com/NexPB/AnimeHD.bundle">Github</a> just download the
                                zip file
                                and install the plugin.</p>
                        </div>
                    </div>
                    <div class="met_kabubu_accordion_item">
                        <a href="#" class="met_kabubu_accordion_sign"><i class="icon-plus met_color"></i></a>

                        <div class="met_kabubu_accordion_detail">
                            <a href="#" class="met_kabubu_item_title">How to install plugins on Plex?</a>

                            <p class="met_kabubu_accordion_descr"><a
                                    href="https://support.plex.tv/hc/en-us/articles/201187656)">Check
                                    out this Plex support guide</a></p>
                        </div>
                    </div>
                    <div class="met_kabubu_accordion_item">
                        <a href="#" class="met_kabubu_accordion_sign"><i class="icon-plus met_color"></i></a>

                        <div class="met_kabubu_accordion_detail">
                            <a href="#" class="met_kabubu_item_title">What is Plex?</a>

                            <p class="met_kabubu_accordion_descr">Check out their website <a
                                    href="https://plex.tv/features">features</a></p>
                        </div>
                    </div>
                    <div class="met_kabubu_accordion_item">
                        <a href="#" class="met_kabubu_accordion_sign"><i class="icon-plus met_color"></i></a>

                        <div class="met_kabubu_accordion_detail">
                            <a href="#" class="met_kabubu_item_title">Download Plex?</a>

                            <p class="met_kabubu_accordion_descr"><a href="https://plex.tv/">https://plex.tv/</a></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="span6">
                <div class="clearfix">
                    <h3 class="met_title_with_childs pull-left">XBMC Add-on : AnimeHD
                        <span class="met_subtitle">FREQUENTLY ASKED QUESTIONS ABOUT XBMC</span>
                    </h3>
                </div>
                <div class="met_kabubu_accordion">
                    <div class="met_kabubu_accordion_item met_kabubu_accordion_item_active">
                        <a href="#" class="met_kabubu_accordion_sign"><i class="icon-minus met_color"></i></a>

                        <div class="met_kabubu_accordion_detail">
                            <a href="#" class="met_kabubu_item_title">How to get AnimeHD?</a>

                            <p class="met_kabubu_accordion_descr" style="display: block;">AnimeHD is available at <a
                                    href="https://github.com/NexPB/plugin.video.masterani">Github</a> just download the
                                zip file
                                and install the add-on.</p>
                        </div>
                    </div>
                    <div class="met_kabubu_accordion_item">
                        <a href="#" class="met_kabubu_accordion_sign"><i class="icon-plus met_color"></i></a>

                        <div class="met_kabubu_accordion_detail">
                            <a href="#" class="met_kabubu_item_title">How to install add-ons on XBMC?</a>

                            <p class="met_kabubu_accordion_descr"><a
                                    href="http://wiki.xbmc.org/index.php?title=HOW-TO:Install_an_Add-on_from_a_zip_file">Check
                                    out XBMC wiki guide</a></p>
                        </div>
                    </div>
                    <div class="met_kabubu_accordion_item">
                        <a href="#" class="met_kabubu_accordion_sign"><i class="icon-plus met_color"></i></a>

                        <div class="met_kabubu_accordion_detail">
                            <a href="#" class="met_kabubu_item_title">What is XBMC?</a>

                            <p class="met_kabubu_accordion_descr">XBMC is an award-winning free and open source (GPL)
                                software
                                media player and entertainment hub that can be installed on Linux, OSX, Windows, iOS,
                                and
                                Android, featuring a 10-foot user interface for use with televisions and remote
                                controls. It
                                allows users to play and view most videos, music, podcasts, and other digital media
                                files from
                                local and network storage media and the internet.</p>
                        </div>
                    </div>
                    <div class="met_kabubu_accordion_item">
                        <a href="#" class="met_kabubu_accordion_sign"><i class="icon-plus met_color"></i></a>

                        <div class="met_kabubu_accordion_detail">
                            <a href="#" class="met_kabubu_item_title">Download XBMC?</a>

                            <p class="met_kabubu_accordion_descr"><a href="http://xbmc.org/">http://xbmc.org/</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="clearfix">
            <h3 class="met_title_with_childs pull-left">DISQUS
                <span class="met_subtitle">DISCUS OR REQUEST ANIME FOR ANIMEHD ADD-ON</span>
            </h3>
        </div>
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