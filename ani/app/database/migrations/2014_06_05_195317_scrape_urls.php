<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ScrapeUrls extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('scrape_urls', function($table)
        {
            $table->integer('anime_id')->unique();
            $table->string('suffix_animerush', 255)->nullable();
            $table->string('suffix_rawranime', 255)->nullable();
            $table->string('othername', 255)->nullable();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('scrape_urls');
	}

}
