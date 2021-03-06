<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
Route::group(array('middleware' => 'forceSSL'), function() {
Route::get('/', function () {
    return redirect('/home');
});
Auth::routes();
Route::get('/home',['uses'=>'HomeController@index','middleware'=>'auth']);
Route::get('/record/{id}',['uses'=>'IndexController@show','middleware'=>'auth']);
Route::get('/logout',function ()
{
	Auth::logout();
	return redirect('/');
});
});
