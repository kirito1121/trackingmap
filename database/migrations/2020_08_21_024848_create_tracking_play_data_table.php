<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackingPlayDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracking_play_data', function (Blueprint $table) {
            $table->id();
            $table->string("level")->nullable();
            $table->string("user_count")->nullable();
            $table->string("play_count")->nullable();
            $table->string("start_moves")->nullable();
            $table->string("win_count")->nullable();
            $table->string("win_no_booster")->nullable();
            $table->string("win_extra_move")->nullable();
            $table->string("win_merge_rb")->nullable();
            $table->string("win_garage_scs")->nullable();
            $table->string("win_booster_scs")->nullable();
            $table->string("win_moves")->nullable();
            $table->string("win_moves_range")->nullable();
            $table->string("win_no_booster_moves")->nullable();
            $table->string("win_no_booster_moves_range")->nullable();
            $table->string("win_scores")->nullable();
            $table->string("win_scores_range")->nullable();
            $table->string("win_no_booster_scores")->nullable();
            $table->string("win_no_booster_scores_range")->nullable();
            $table->string("win_time")->nullable();
            $table->string("lose_moves")->nullable();
            $table->string("lose_moves_range")->nullable();
            $table->string("lose_time")->nullable();
            $table->string("lose_rate")->nullable();
            $table->string("lose_no_booster_rate")->nullable();
            $table->string("used_coins")->nullable();
            $table->string("win_used_coins")->nullable();
            $table->string("subLevel")->nullable();
            $table->string("appVersion")->nullable();
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
        Schema::dropIfExists('tracking_play_data');
    }
}
