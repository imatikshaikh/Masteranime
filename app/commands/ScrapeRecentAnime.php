<?php

use Illuminate\Console\Command;

class ScrapeRecentAnime extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ScrapeRecentAnime';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrapes anime videos that have been recently released.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        RecentAnime::scrape();
    }

}
