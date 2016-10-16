<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|


Route::get('/', function () {
    return view('welcome');
});

*/

Route::get("/", "IndexController@index")->name("index");
Route::match(["get", "post"],"/getEntitiesList", "IndexController@getEntitiesList")->name("entities_list");
Route::get("/putNextPosition", "IndexController@putNextPosition")->name("put_next_position");
Route::post("/saveConfig", "IndexController@saveConfig")->name("save_config");
Route::post("/appState", "IndexController@changeAppState")->name("app_state");
Route::get("/entities", "EntitiesController@index")->name("entities");
Route::get("/entitiesList", "EntitiesController@getEntities")->name("entities_rows");
Route::get("/entities/{id}/edit", "EntitiesController@editEntities")->name("entities_edit");
Route::put("/entities/{id}/edit", "EntitiesController@editEntities")->name("entities_edit");




