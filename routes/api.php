<?php

use Illuminate\Http\Request;

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
Route::group(array('middleware' => 'forceSSL'), function() {
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');
Route::post('/save_record','RecordController@save_record');
Route::post('/initial_transcription','TranscriptionController@initial_transcription');
Route::post('/initial_transcription_accepted','TranscriptionController@initial_transcription_accepted');
Route::post('/add_pending_record','RecordController@add_pending_record');
Route::post('/add_transcription_by_user','TranscriptionController@add_transcription_by_user');
Route::post('/refresh_user','RecordController@refresh_user');
Route::post('/declined_transcription','TranscriptionController@decline_transcription');
Route::post('/refresh','TranscriptionController@refresh');
Route::post('/processing_record','RecordController@processing');
Route::post('/timeout','TranscriptionController@timeout');
Route::post('/empty','TranscriptionController@add_empty_transcription');
Route::post('/cancel','RecordController@cancel');
});
