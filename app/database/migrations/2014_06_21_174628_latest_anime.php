<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LatestAnime extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('latest_anime', function($table){
            $table->increments('id');
            $table->integer('anime_id');
            $table->string('name', 100);
            $table->string('episode', 11);
            $table->string('img', 255)->nullable();
            $table->timestamps();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('latest_anime');
	}

}
