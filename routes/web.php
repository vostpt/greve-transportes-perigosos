<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EntriesController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\ExternalAuthController;
use App\Http\Controllers\FuelStationsController;
use App\Http\Controllers\GraphsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OptionsController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'map')->name('map');
Route::view('/stats', 'stats')->name('stats');
Route::view('/arcgis', 'arcgis')->name('arcgis');
Route::view('/about', 'about')->name('about');
Route::view('/media', 'media')->name('media');
Route::view('/help', 'help')->name('help');

Route::prefix('graphs')->name('graphs.')->controller(GraphsController::class)->group(function () {
    Route::get('/stats', 'stats')->name('stats');
    Route::get('/brands', 'brands')->name('brands');
    Route::get('/entries', 'entries')->name('entries');
});

Route::prefix('error')->name('error.')->controller(ErrorController::class)->group(function () {
    Route::get('/', 'new')->name('new');
    Route::get('/edit', 'edit')->name('edit');
    Route::get('/submitted', 'submitted')->name('submitted');
    Route::post('/push', 'push')->middleware('throttle:6,1')->name('push');
});

Route::post('/entries/add', [EntriesController::class, 'add'])
    ->middleware('throttle:20,1')
    ->name('entries.add');

Route::prefix('panel')->name('panel.')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
    Route::post('/login', [LoginController::class, 'login'])->middleware(['guest', 'throttle:login']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

    Route::middleware('auth')->group(function () {
        Route::get('/home', [HomeController::class, 'index'])->name('home');

        Route::prefix('users')->name('users.')->controller(UsersController::class)->group(function () {
            Route::get('/add', 'add')->name('add');
            Route::post('/create', 'create')->name('create');
            Route::get('/list', 'list')->name('list');
            Route::get('/password', 'password')->name('password');
            Route::get('/fetch/verified', 'fetchVerified')->name('fetch.verified');
            Route::get('/fetch/not_verified', 'fetchNotVerified')->name('fetch.not_verified');
            Route::post('/verify', 'verify')->name('verify');
            Route::post('/delete', 'delete')->name('delete');
            Route::post('/update-password', 'updatePassword')->name('updatePassword');
        });

        Route::prefix('entries')->name('entries.')->controller(EntriesController::class)->group(function () {
            Route::get('/list', 'list')->name('list');
            Route::get('/fetch/pending', 'fetchPending')->name('fetch.pending');
            Route::post('/push', 'push')->name('push');
        });

        Route::prefix('stations')->name('stations.')->controller(FuelStationsController::class)->group(function () {
            Route::get('/list', 'list')->name('list');
            Route::get('/fetch/all', 'fetchAll')->name('fetch.all');
            Route::post('/update', 'update')->name('update');
            Route::post('/update-available', 'updateAvailable')->name('update_available');
        });

        Route::prefix('options')->name('options.')->controller(OptionsController::class)->group(function () {
            Route::get('/list', 'list')->name('list');
            Route::get('/fetch/all', 'fetchAll')->name('fetch.all');
            Route::post('/update', 'update')->name('update');
        });

        Route::prefix('externalauth')->name('externalauth.')->controller(ExternalAuthController::class)->group(function () {
            Route::get('/add', 'add')->name('add');
            Route::post('/create', 'create')->name('create');
            Route::get('/list', 'list')->name('list');
            Route::get('/fetch/all', 'fetchAll')->name('fetch.all');
            Route::post('/delete', 'delete')->name('delete');
        });
    });
});
