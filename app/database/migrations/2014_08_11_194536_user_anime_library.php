<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserAnimeLibrary extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_anime_library', function ($table) {
            $table->integer('user_id');
            $table->integer('anime_id');
            $table->boolean('is_fav')->default(false);
            $table->tinyInteger('library_status')->default(0);
            $table->string('last_watched_episode', 11)->nullable();
            $table->date('last_watched_time')->nullable();
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
        Schema::drop('user_anime_library');
    }

}
