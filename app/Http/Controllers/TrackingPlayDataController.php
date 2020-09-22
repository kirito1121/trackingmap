<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TrackingPlayDataController extends Controller
{
    public function getDataPlay(Request $request){
        if(!$request->level){
            return null;
        }
        $sql = "SELECT level,count(distinct UserId) as userCount, avg(UsedCoins) as conversions, count(UserId) as playTime, count(UsedCoins) as usedCoins
                from levelanalytics where level in ($request->level,$request->level+1) and Sublevel = $request->sublevel
                and AppVersion = '$request->appVersion' group by level";

        $sqlLose = "SELECT level,count(UserId) as userCount
                from levelanalytics where level = $request->level and Endtype = 'Lose' and Sublevel = $request->sublevel
                and AppVersion = '$request->appVersion' group by level";

        $userCount = collect(DB::select($sql))->toArray();
        $loseCount = collect(DB::select($sqlLose))->toArray();
        if($userCount && $loseCount){
            return [
                 (1 - $userCount[1]->userCount / $userCount[0]->userCount)*100,
                 ($loseCount[0]->userCount / $userCount[0]->playTime)*100,
                 $userCount[0]->conversions,
            ];
        }
        return [];
    }
}
