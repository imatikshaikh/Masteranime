<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Series extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('series', function($table)
        {
            $table->increments('id');
            $table->integer('mal_id')->unique();
            $table->string('hum_id')->unique();
            $table->string('name', 100);
            $table->string('english_name', 100)->nullable();
            $table->string('name_synonym_2', 100)->nullable();
            $table->string('name_synonym_3', 100)->nullable();
            $table->string('mal_image', 255)->nullable();
            $table->string('cover', 255)->nullable();
            $table->string('thumbnail', 255)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->longText('description');
            $table->tinyInteger('status');
            $table->tinyInteger('type');
            $table->tinyInteger('mal_total_eps')->nullable();
            $table->string('genres', 255)->nullable();
            $table->string('screencaps', 555)->nullable();
            $table->string('youtube_trailer_id', 50)->nullable();
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
		Schema::drop('series');
	}

}
