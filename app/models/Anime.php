<?php

class Anime extends Eloquent
{

    protected $table = 'series';
    protected $fillable = ['mal_id', 'thumbnail'];

    public static function getScreencaps($row)
    {
        if (!empty($row->screencaps)) {
            return explode(', ', $row->screencaps);
        }
        return null;
    }

    public static function getCover($row)
    {
        if (empty($row->cover)) {
            return $row->mal_image;
        }
        return $row->cover;
    }

    public static function getThumbnail($row)
    {
        if (empty($row->thumbnail)) {
            return Anime::getCover($row);
        }
        return $row->thumbnail;
    }

    public static function getSynonyms($row)
    {
        $count = 0;
        $str = '';
        if (!empty($row->english_name)) {
            $str .= $row->english_name;
            $count++;
        }
        if (!empty($row->name_synonym_2)) {
            if ($count > 0)
                $str .= ' - ';
            $str .= $row->name_synonym_2;
            $count++;
        }
        if (!empty($row->name_synonym_3)) {
            if ($count > 0)
                $str .= ' - ';
            $str .= $row->name_synonym_3;
        }
        return $str;
    }

    public static function getAnimeList($series, $is_admin = false)
    {
        foreach ($series as $serie) {
            echo '<li><a href="' . URL::to('anime/' . $serie->id . '/' . str_replace(array(" ", "/", "?"), "_", $serie->name)) . '">';
            $synonyms = Anime::getSynonyms($serie);
            if (!empty($synonyms)) {
                echo '<span data-toggle="tooltip-right" title="' . $synonyms . '">' . $serie->name . '</span>';
            } else {
                echo '<span>' . $serie->name . '</span>';
            }
            echo '<div class="pull-right" style="margin-top: -3px;">';
            if ($serie->status == 1) {
                echo '<span class="tag-red">ongoing</span>';
            } else if ($serie->type == 2) {
                echo '<span class="tag-blue">movie</span>';
            }
            if ($is_admin) {
                echo '<button style="margin-left: 5px;" id="update_mirrors_button" class="btn-small btn-success"><input type="hidden" name="anime_id" value="' . $serie->id . '"/><span class="icon-download-alt"></span>' . $serie->id . '</button>';
            }
            echo '</div></a></li>';
        }
    }
}