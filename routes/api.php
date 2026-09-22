<?php

declare(strict_types=1);

use App\Http\Controllers\APIController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->controller(APIController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::post('/info', 'info')->name('info');
    Route::post('/fetch', 'fetch')->name('fetch');
    Route::post('/push', 'push')->middleware('throttle:60,1')->name('push');
    Route::post('/add', 'add')->middleware('throttle:20,1')->name('add');
    Route::post('/change', 'change')->middleware('throttle:60,1')->name('change');
    Route::get('/fetchcsv', 'fetchCsv')->name('fetch_csv');
    Route::get('/fetchapicsv', 'fetchApiCsv')->name('fetch_api_csv');
});
