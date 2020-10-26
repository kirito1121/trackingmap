<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrackingPlayData extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
            "level",
            "user_count",
            "play_count" ,
            "start_moves",
            "win_count" ,
            "win_no_booster",
            "win_extra_move",
            "win_merge_rb",
            "win_garage_scs",
            "win_booster_scs",
            "win_moves",
            "win_moves_range",
            "win_no_booster_moves",
            "win_no_booster_moves_range",
            "win_scores" ,
            "win_scores_range",
            "win_no_booster_scores",
            "win_no_booster_scores_range",
            "win_time",
            "lose_moves",
            "lose_moves_range",
            "lose_time",
            "lose_rate",
            "lose_no_booster_rate",
            "used_coins",
            "win_used_coins",
            "subLevel",
            "appVersion",
            "levelType"
    ];
}
