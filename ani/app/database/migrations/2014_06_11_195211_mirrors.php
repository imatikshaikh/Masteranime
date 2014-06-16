<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Mirrors extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('mirrors', function($table)
        {
            $table->increments('id');
            $table->integer('anime_id');
            $table->string('episode', 11);
            $table->string('src', 255);
            $table->string('host', 255);
            $table->integer('quality');
            $table->tinyInteger('subbed');
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
        Schema::drop('mirrors');
	}

}
