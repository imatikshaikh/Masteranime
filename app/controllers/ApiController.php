<?php

class ApiController extends BaseController
{
    public function getAll()
    {
        return View::make('api.xml', ['display' => 'anime', 'content' => DB::table('series')->select('id', 'name')->get()]);
    }

    public function getAnime($id)
    {
        return View::make('api.xml', ['display' => 'episodes', 'content' => MasterAnime::getEpisodes($id)]);
    }

    public function getEpisode($id, $episode)
    {
        return View::make('api.xml', ['display' => 'episode', 'content' => MasterAnime::getApiEpisode($id, $episode)]);
    }
}