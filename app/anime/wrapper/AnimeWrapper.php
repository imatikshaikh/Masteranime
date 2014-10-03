<?php

/**
 * Created by PhpStorm.
 * User: Lorenzo
 * Date: 13/08/14
 * Time: 9:37
 */
class AnimeWrapper
{
    public static $genres = ["Action", "Adventure", "Cars", "Comedy", "Dementia", "Demons", "Drama", "Ecchi", "Fantasy", "Game", "Harem", "Historical", "Horror", "Kids", "Magic", "Martial Arts", "Military", "Music", "Mystery", "Parody", "Police", "Psychological", "Romance", "Samurai", "School", "Sci-Fi", "Shoujo Ai", "Shounen Ai", "Slice of Life", "Space", "Sports", "Supernatural", "Super Power", "Thriller", "Vampire", "Yaoi", "Yuri"];

    public static function getSubbed($subbed)
    {
        switch ($subbed) {
            case 1:
                return "Subbed";
            case 2:
                return "Dubbed";
        }
    }

    public static function  getStatusString($status)
    {
        switch ($status) {
            case 2:
                return 'Not yet aired';
            case 1:
                return 'Ongoing';
            default:
                return 'Completed';
        }
    }

    public static function getTypeString($type)
    {
        switch ($type) {
            case 3:
                return 'Special';
            case 2:
                return 'Movie';
            case 1:
                return 'OVA';
            default:
                return 'TV';
        }
    }

    public static function getStatusInt($status)
    {
        switch ($status) {
            case 'Finished Airing':
                return 0;
            case 'Currently Airing':
                return 1;
            case 'Not yet aired':
                return 2;
        }
    }

    public static function getTypeInt($type)
    {
        switch ($type) {
            case 'Special':
                return 3;
            case 'Movie':
                return 2;
            case 'OVA':
                return 1;
            case 'TV':
                return 0;
            default:
                return 0;
        }
    }

}