<?php
declare(strict_types=1);

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
    return view('map');
})->name('map');


Auth::routes(['register' => false, 'verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('users')->name('users.')->middleware('auth')->group(function () {
    Route::get('add', 'UsersController@add')->name('add');
    Route::post('create', 'UsersController@create')->name('create');
    Route::get('list', 'UsersController@list')->name('list');
    Route::prefix('fetch')->name('fetch.')->group(function () {
        Route::get('verified', 'UsersController@fetch_verified')->name('verified');
        Route::get('not_verified', 'UsersController@fetch_not_verified')->name('not_verified');
    });
    Route::post('verify', 'UsersController@verify')->name('verify');
    Route::post('delete', 'UsersController@delete')->name('delete');
});

Route::prefix('entries')->name('entries.')->middleware('auth')->group(function () {
    Route::get('list', 'EntriesController@list')->name('list');
    Route::prefix('fetch')->name('fetch.')->group(function () {
        Route::get('pending', 'EntriesController@fetch_pending')->name('pending');
    });
    Route::post('push', 'EntriesController@push')->name('push');
});

Route::prefix('stations')->name('stations.')->middleware('auth')->group(function () {
    Route::get('list', 'FuelStationsController@list')->name('list');
    Route::prefix('fetch')->name('fetch.')->group(function () {
        Route::get('pending', 'FuelStationsController@fetch_all')->name('all');
    });
    Route::post('update', 'FuelStationsController@update')->name('update');
});

