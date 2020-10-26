<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class VersionController extends Controller
{
    public function index()
    {
        return Cache::rememberForever('versions', function () {
            return collect(DB::table('versions')->orderBy('day_start')->pluck('version'))->toArray();
        });
    }

    public function entity()
    {
        return Cache::rememberForever('entity', function () {
            return Config::get("entity");
        });
    }
}
