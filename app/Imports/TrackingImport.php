<?php

namespace App\Imports;

use App\TrackingPlayData;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
class TrackingImport implements ToModel
{
    use Importable;
    /**
     * @param array $row
     *
     * @return TrackingImport|null
     */
    public function model(array $row)
    {
        $r = mb_split('-',$row[0]);
        return new TrackingPlayData([
            "level"=> $r[0] ?? null,
            "user_count" => $row[1],
            "play_count" => $row[2],
            "start_moves" => $row[3],
            "win_count" => $row[4],
            "win_no_booster" => $row[5],
            "win_extra_move" => $row[6],
            "win_merge_rb" => $row[7],
            "win_garage_scs"=> $row[8],
            "win_booster_scs"=> $row[9],
            "win_moves"=> $row[10],
            "win_moves_range"=> $row[11],
            "win_no_booster_moves"=> $row[12],
            "win_no_booster_moves_range" => $row[13],
            "win_scores" => $row[14],
            "win_scores_range" => $row[15],
            "win_no_booster_scores" => $row[16],
            "win_no_booster_scores_range" => $row[17],
            "win_time" => $row[18],
            "lose_moves" => $row[19],
            "lose_moves_range" => $row[20],
            "lose_time" => $row[21],
            "lose_rate" => $row[22],
            "lose_no_booster_rate" => $row[23],
            "used_coins" => $row[24],
            "win_used_coins" => $row[25],
            "subLevel" => $r[1] ?? null,
            "appVersion" => '1.4.20',
            "levelType" => 2
        ]);
    }
}
