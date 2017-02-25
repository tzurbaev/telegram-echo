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

Route::group(['middleware' => 'auth'], function () {
    Route::put('/settings', 'SettingsController@update')->name('api.settings.update');

    Route::resource('/channels', 'ChannelsController', [
        'only' => ['index', 'store', 'show', 'update', 'destroy'],
        'names' => [
            'index' => 'api.channels.index',
            'store' => 'api.channels.store',
            'show' => 'api.channels.show',
            'update' => 'api.channels.update',
            'destroy' => 'api.channels.destroy',
        ],
    ]);

    Route::group(['prefix' => 'channels/{channel}/members'], function () {
        Route::get('/', 'ChannelMembersController@index')->name('api.channels.members.index');
        Route::post('/', 'ChannelMembersController@store')->name('api.channels.members.store');
        Route::delete('/', 'ChannelMembersController@destroy')->name('api.channels.members.destroy');
    });

    Route::resource('/bots', 'BotsController', [
        'only' => ['index', 'store', 'show', 'update', 'destroy'],
        'names' => [
            'index' => 'api.bots.index',
            'store' => 'api.bots.store',
            'show' => 'api.bots.show',
            'update' => 'api.bots.update',
            'destroy' => 'api.bots.destroy',
        ],
    ]);
});
