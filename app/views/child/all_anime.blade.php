<div class="row-fluid" style="margin-bottom: 0;">
    <div class="span12">
        <ul id="animelist" class="nav nav-tabs nav-stacked anime-list" style="overflow: visible;">
            <?php
            function print_anime($series)
            {
                if (Sentry::check() && Sentry::getUser()->isSuperUser()) {
                    Anime::getAnimeList($series, true);
                } else {
                    Anime::getAnimeList($series);
                }
            }

            if (!empty($search_display)) {
                if ($search_display["view"]) {
                    if (empty($search_display["series"])) {
                        echo '<li><a href="#">Didn\'t find any anime mathing your keyword. (Displaying ALL anime)</a></li>';
                    } else {
                        print_anime($search_display["series"]);
                    }
                } else {
                    if (empty($search_display["series"])) {
                        echo '<li style="margin-bottom: 20px;"><a href="#">Didn\'t find any anime mathing your keyword. (Displaying ALL anime)</a></li>';
                        $series = DB::table('series')->select('id', 'name', 'english_name', 'name_synonym_2', 'name_synonym_3', 'type', 'status')->orderBy('name', 'ASC')->get();
                        print_anime($series);
                    } else {
                        print_anime($search_display["series"]);
                    }
                }
            } else {
                $series = DB::table('series')->select('id', 'name', 'english_name', 'name_synonym_2', 'name_synonym_3', 'type', 'status')->orderBy('name', 'ASC')->get();
                print_anime($series);
            }
            ?>
        </ul>
        <script type="text/javascript">
            $("[data-toggle=tooltip-right]").tooltip({ placement: 'right'});
            var $container = $('.anime-list').isotope({
                itemSelector: '.item'
            });
            $('.met_filters a').click(function () {
                var filterValue = $(this).attr('data-filter');
                $container.isotope({ filter: filterValue });
            });
        </script>
    </div>
</div>