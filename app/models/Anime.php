<?php

class Anime extends Eloquent {

    protected $table = 'series';
    protected $fillable = ['mal_id'];

    public static function getScreencaps($row) {
        if (!empty($row->screencaps)) {
            return explode(', ', $row->screencaps);
        }
        return null;
    }

    public static function getCover($row) {
        if (empty($row->cover)) {
            return $row->mal_image;
        }
        return $row->cover;
    }

    public static function getThumbnail($row) {
        if (empty($row->thumbnail)) {
            return Anime::getCover($row);
        }
        return $row->thumbnail;
    }

    public static function getSynonyms($row) {
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

}