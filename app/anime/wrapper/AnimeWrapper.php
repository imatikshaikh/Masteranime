<?php

/**
 * Created by PhpStorm.
 * User: Lorenzo
 * Date: 13/08/14
 * Time: 9:37
 */
class AnimeWrappper
{

    public static function  getStatusString($status)
    {
        switch ($status) {
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

            default:
                return 0;
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