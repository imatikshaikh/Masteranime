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
        Schema::dropIfExists('anime_favorites');
        if (Schema::hasTable('lastwatched_anime')) {
            if (Schema::hasColumn('lastwatched_anime', 'created_at')) {
                Schema::table('lastwatched_anime', function ($table) {
                    $table->dropColumn('created_at');
                });
            }
            if (!Schema::hasColumn('lastwatched_anime', 'last_watched_episode')) {
                if (Schema::hasColumn('lastwatched_anime', 'episode')) {
                    Schema::table('lastwatched_anime', function ($table) {
                        $table->renameColumn('episode', 'last_watched_episode')->nullable()->default(null);

                    });
                } else {
                    Schema::table('lastwatched_anime', function ($table) {
                        $table->string('last_watched_episode', 11)->nullable();
                    });
                }
            }
            if (!Schema::hasColumn('lastwatched_anime', 'last_watched_time')) {
                if (Schema::hasColumn('lastwatched_anime', 'updated_at')) {
                    Schema::table('lastwatched_anime', function ($table) {
                        $table->renameColumn('updated_at', 'last_watched_time')->nullable()->default(null);
                    });
                } else {
                    Schema::table('lastwatched_anime', function ($table) {
                        $table->timestamp('last_watched_time')->nullable();
                    });
                }
            }
            if (!Schema::hasColumn('lastwatched_anime', 'is_fav')) {
                Schema::table('lastwatched_anime', function ($table) {
                    $table->tinyInteger('library_status')->default(0);
                });
            }
            if (!Schema::hasColumn('lastwatched_anime', 'library_status')) {
                Schema::table('lastwatched_anime', function ($table) {
                    $table->tinyInteger('library_status')->default(0);
                });
            }
            Schema::rename('lastwatched_anime', 'user_anime_library');
            Schema::table('user_anime_library', function ($table) {
                $table->timestamps();
            });
        } else {
            Schema::create('user_anime_library', function ($table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->integer('anime_id');
                $table->boolean('is_fav')->default(false);
                $table->tinyInteger('library_status')->default(0);
                $table->string('last_watched_episode', 11)->nullable()->default(null);
                $table->timestamp('last_watched_time')->nullable()->default(null);
                $table->timestamps();
            });
        }
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
