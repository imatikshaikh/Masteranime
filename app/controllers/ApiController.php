<?php

class ApiController extends BaseController
{
    public function getAll()
    {
        return View::make('api.xml', ['display' => 'anime', 'content' => DB::table('series')->select('id', 'name', 'mal_image')->orderBy('name', 'ASC')->get()]);
    }

    public function getOngoing()
    {
        return View::make('api.xml', ['display' => 'anime', 'content' => DB::table('series')->whereRaw('status = ?', array(1))->select('id', 'name', 'mal_image')->orderBy('name', 'ASC')->get()]);
    }

    public function getSearch($keyword)
    {
        return View::make('api.xml', ['display' => 'anime', 'content' => DB::table('series')->whereRaw('name LIKE ? or english_name LIKE ? or name_synonym_2 LIKE ? or name_synonym_3 LIKE ?', array('%' . $keyword . '%', '%' . $keyword . '%', '%' . $keyword . '%', '%' . $keyword . '%'))->select('id', 'name', 'mal_image')->orderBy('name', 'ASC')->get()]);
    }

    public function getAnime($id)
    {
        return View::make('api.xml', ['display' => 'episodes', 'content' => MasterAnime::getEpisodes($id)]);
    }

    public function getEpisode($id, $episode)
    {
        return View::make('api.xml', ['display' => 'episode', 'content' => MasterAnime::getApiEpisode($id, $episode)]);
    }

    public function getLatest()
    {
        return View::make('api.xml', ['display' => 'latest', 'content' => Latest::orderBy('created_at', 'DESC')->orderby(DB::raw('CAST(episode AS SIGNED)'), 'DESC')->take(25)->get()]);
    }

}