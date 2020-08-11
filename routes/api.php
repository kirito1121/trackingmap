<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('versions', 'VersionController@index');
Route::get('entity', 'VersionController@entity');
Route::get('viewLevel', 'LevelController@viewLevel');
Route::delete('level/delete', 'LevelController@delete');


Route::post('pushLevel', 'LevelController@pushLevel');
Route::post('pushLevels', 'LevelController@pushLevels');
Route::get('getLevel', 'LevelController@getLevel');
Route::get('getLevels', 'LevelController@getLevels');
