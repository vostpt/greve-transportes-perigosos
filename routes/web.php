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

Route::get('/stats', function () {
    return view('stats');
})->name('stats');

Route::get('/arcgis', function () {
    return view('arcgis');
})->name('arcgis');


Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/media', function () {
    return view('media');
})->name('media');



Auth::routes(['register' => false, 'verify' => true]);
Route::get('panel/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('panel/login', 'Auth\LoginController@login');
Route::post('panel/logout', 'Auth\LoginController@logout')->name('logout');
Route::get('panel/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('panel/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('panel/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('panel/password/reset', 'Auth\ResetPasswordController@reset');


Route::prefix('panel')->group(function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::prefix('users')->name('users.')->middleware('auth')->group(function () {
        Route::get('add', 'UsersController@add')->name('add');
        Route::post('create', 'UsersController@create')->name('create');
        Route::get('list', 'UsersController@list')->name('list');
        Route::prefix('fetch')->name('fetch.')->group(function () {
            Route::get('verified', 'UsersController@fetch_verified')->name('verified');
            Route::get('not_verified', 'UsersController@fetch_not_verified')->name('not_verified');
        });
        Route::get('password', 'UsersController@password')->name('password');
        Route::post('verify', 'UsersController@verify')->name('verify');
        Route::post('delete', 'UsersController@delete')->name('delete');
        Route::post('updatePassword', 'UsersController@updatePassword')->name('updatePassword');
    });

    Route::prefix('entries')->name('entries.')->group(function () {
        Route::get('list', 'EntriesController@list')->middleware('auth')->name('list');
        Route::prefix('fetch')->name('fetch.')->middleware('auth')->group(function () {
            Route::get('pending', 'EntriesController@fetch_pending')->name('pending');
        });
        Route::post('push', 'EntriesController@push')->middleware('auth')->name('push');
        Route::post('delete', 'EntriesController@delete')->middleware('auth')->name('delete');
        Route::post('add', 'EntriesController@add')->name('add');
    });

    Route::prefix('stations')->name('stations.')->middleware('auth')->group(function () {
        Route::get('list', 'FuelStationsController@list')->name('list');
        Route::prefix('fetch')->name('fetch.')->group(function () {
            Route::get('pending', 'FuelStationsController@fetch_all')->name('all');
        });
        Route::post('update', 'FuelStationsController@update')->name('update');
        Route::post('update-available', 'FuelStationsController@updateAvailable')->name('update_available');
    });

    Route::prefix('options')->name('options.')->middleware('auth')->group(function () {
        Route::get('list', 'OptionsController@list')->name('list');
        Route::prefix('fetch')->name('fetch.')->group(function () {
            Route::get('all', 'OptionsController@fetch_all')->name('all');
        });
        Route::post('update', 'OptionsController@update')->name('update');
    });

    Route::prefix('externalauth')->name('externalauth.')->middleware('auth')->group(function () {
        Route::get('add', 'ExternalAuthController@add')->name('add');
        Route::post('create', 'ExternalAuthController@create')->name('create');
        Route::get('list', 'ExternalAuthController@list')->name('list');
        Route::prefix('fetch')->name('fetch.')->group(function () {
            Route::get('all', 'ExternalAuthController@fetch_all')->name('all');
        });
        Route::post('delete', 'ExternalAuthController@delete')->name('delete');
    });
});

Route::prefix('api/v1')->name('api.')->group(function () {
    Route::get('/', 'APIController@home')->name('home');
    Route::post('fetch', 'APIController@fetch')->name('fetch');
    Route::post('push', 'APIController@push')->name('push');
    Route::post('info', 'APIController@info')->name('info');
    Route::post('add', 'APIController@add')->name('add');
    Route::post('change', 'APIController@change')->name('change');
    Route::get('fetchcsv', 'APIController@fetch_csv')->name('fetch_csv');
    Route::get('fetchapicsv', 'APIController@fetch_api_csv')->name('fetch_api_csv');
});


Route::prefix('graphs')->name('graphs.')->group(function () {
    Route::get('/stats', 'GraphsController@stats')->name('stats');
    Route::get('/brands', 'GraphsController@brands')->name('brands');
    Route::get('/entries', 'GraphsController@entries')->name('entries');
});


Route::prefix('error')->name('error.')->group(function () {
    Route::get('/', 'ErrorController@new')->name('new');
    Route::get('edit', 'ErrorController@edit')->name('edit');
    Route::get('submitted', 'ErrorController@submitted')->name('submitted');
    Route::post('push', 'ErrorController@push')->name('push');
});
