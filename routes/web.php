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
    return view('welcome');
});

// facebook webHook
//route for verification token
Route::get('/facebookAPI', "MainController@facebookReceive")->middleware("verifyFacebookToken") ;
// facebook send message to
Route::post('/facebookAPI', "MainController@facebookReceive")->middleware("verifyFacebookToken") ;

Route::get('getUser', "MainController@getUserTest");