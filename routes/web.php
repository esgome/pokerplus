<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

$datos=['hello'];
	return view('welcome');
    
});

Route::get('/reload', function(){

	return view('reloadview');
});

Route::get('hand', 'HandController@hand');

Route::get('/flop', 'HandController@flop');

Route::get('/turn', 'HandController@turn');

Route::get('/river', 'HandController@river');

Route::get('/game', 'HandController@game');

Route::post('bet', 'HandController@bet');

Route::get('/vistas', 'HandController@vistas');
Auth::routes();
Route::get('/lobby', 'LobbyController@index');
Route::post('lobby', 'LobbyController@register');
Route::get('/home', 'HomeController@index');
Route::get('/table/{ply}', function($ply){

	return view('table', compact('ply'));


});
