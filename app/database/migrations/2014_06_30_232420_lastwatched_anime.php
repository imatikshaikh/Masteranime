<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LastwatchedAnime extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lastwatched_anime', function ($table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('anime_id');
            $table->string('episode', 11);
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
        Schema::drop('lastwatched_anime');
    }

}
