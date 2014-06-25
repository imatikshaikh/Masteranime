<div class="row-fluid">
    <div class="span12">
        <ul id="animelist" class="nav nav-tabs nav-stacked">
            <?php
            if (!empty($search_display)) {
                if ($search_display["view"]) {
                    if (empty($search_display["series"])) {
                        echo '<li><a href="#">Didn\'t find any anime mathing your keyword.</a></li>';
                    } else {
                        Anime::getAnimeList($search_display["series"]);
                    }
                } else {
                    if (empty($search_display["series"])) {
                        echo '<li style="margin-bottom: 10px;">Didn\'t find any anime mathing your keyword. (Displaying ALL anime)</li>';
                        $series = DB::table('series')->select('id', 'name', 'english_name', 'name_synonym_2', 'name_synonym_3', 'type', 'status')->orderBy('name', 'ASC')->get();
                        Anime::getAnimeList($series);
                    } else {
                        Anime::getAnimeList($search_display["series"]);
                    }
                }
            } else {
                $series = DB::table('series')->select('id', 'name', 'english_name', 'name_synonym_2', 'name_synonym_3', 'type', 'status')->orderBy('name', 'ASC')->get();
                Anime::getAnimeList($series, isset($is_admin));
            }
            ?>
        </ul>
        <script type="text/javascript">
            $("[data-toggle=tooltip-right]").tooltip({ placement: 'right'});
        </script>
    </div>
</div>