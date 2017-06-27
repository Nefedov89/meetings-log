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

Route::get('/', function() {
    return view('app');
});

Route::get('/empty-date-rate', function() {
    return view('app');
});

Route::get('/get-empty-rate', [
    'as'   => 'get-empty-rate',
    'uses' => 'MeetingsLogController@getEmptyRate',
]);

Route::post('/add-meeting', [
    'as'   => 'add-meeting',
    'uses' => 'MeetingsLogController@addMeeting',
]);