<?php

namespace App\Http\Controllers;

use App\Imports\TrackingImport;
use App\TrackingPlayData;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TrackingPlayDataController extends Controller
{
    public function getDataPlay(Request $request){
        $level = TrackingPlayData::where('level',$request->level)
            ->where('subLevel',$request->subLevel)
            ->where('appVersion',$request->appVersion)
            ->where('levelType',$request->levelType)
            ->first();
        return $level;
    }
    public function import()
    {
        (new TrackingImport)->import(storage_path("app\public\level\Adventure-1.4.20.csv") , null, \Maatwebsite\Excel\Excel::CSV);
        return 'All good!';
    }

    // public function levels(){
    //     $levels = TrackingPlayData::where('subLevel','<>',null)->get();

    //     foreach ($levels as $key => $level) {
    //         $level->levelType = 1;
    //         $level->save();
    //     }

    //     return $levels;
    // }
}
