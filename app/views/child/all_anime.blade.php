<div class="row-fluid">
    <div class="span12">
        <ul class="nav nav-tabs nav-stacked">
            <?php
            $series = Anime::orderBy('name', 'ASC')->get();
            if (!empty($series)) {
                foreach ($series as $serie) {
                    echo '<li><a href="'.URL::to('anime/'. $serie->id .'/'.str_replace(" ", "_", $serie->name)).'">';
                    $synonyms = Anime::getSynonyms($serie);
                    if (!empty($synonyms)) {
                        echo '<span data-toggle="tooltip-right" title="'.$synonyms.'">'.$serie->name.'</span>';
                    } else {
                        echo '<span>'.$serie->name.'</span>';
                    }
                    echo '<div class="pull-right" style="margin-top: -3px;">';
                    if ($serie->status == 1) {
                        echo '<span class="tag-red">ongoing</span>';
                    } else if ($serie->type == 2) {
                        echo '<span class="tag-blue">movie</span>';
                    }
                    if (isset($is_admin) && $is_admin) {
                        echo '<button style="margin-left: 5px;" id="update_mirrors_button" class="btn-small btn-success"><input type="hidden" name="anime_id" value="'.$serie->id.'"/><span class="icon-download-alt"></span></button>';
                    }
                    echo '</div></a></li>';
                }
            }
            ?>
        </ul>
    </div>
</div>