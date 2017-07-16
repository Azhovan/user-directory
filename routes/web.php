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


Auth::routes();


Route::group(['prefix' => 'home', 'middleware' => ['before' => 'auth']], function () {

    Route::get('/', [
        'uses' => 'HomeController@index',
        'as' => 'home'
    ]);


    Route::post('/search', [
        'uses' => 'HomeController@search',
        'as' => 'search'
    ]);


    Route::group(['prefix' => 'profile'], function () {

        Route::get('/view/{id?}', [
            'uses' => 'Profile\ProfileController@view',
            'as' => 'profile.view'
        ]);

        Route::get('/edit', [
            'uses' => 'Profile\ProfileController@edit',
            'as' => 'profile.edit'
        ]);

        Route::post('/update', [
            'uses' => 'Profile\ProfileController@update',
            'as' => 'profile.update'
        ]);


        Route::post('/addFriend', [
            'uses' => 'Profile\ProfileController@addFriend',
            'as' => 'profile.addFriend'
        ]);


    });

});