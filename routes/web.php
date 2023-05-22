<?php

use Illuminate\Support\Facades\Route;

//Controllers
use App\Http\Controllers\HomeHeatingController;

//Middlewares
use App\Http\Middleware\CheckTwoFactor;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/sensor-publish/{app_key}/{room_id}/{temp}/{humidity}', [HomeHeatingController::class, 'sensorPublish'])->name('home-heating.sensor-publish');

Route::get('/get-valve-value/{app_key}/{room_id}', [HomeHeatingController::class, 'getValveValue'])->name('home-heating.valves-set-value');

Route::get('/water-temp/{app_key}/{temp}', [HomeHeatingController::class, 'waterTemp'])->name('home-heating.water-temp');



Route::namespace('App\Http\Livewire\About')->group(function ()
{
    Route::get('/', Index::class)->name('about');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', CheckTwoFactor::class])->group(function ()
{
    Route::namespace('App\Http\Livewire\Dashboard')->group(function ()
    {
        Route::name('dashboard.')->group(function ()
        {
            Route::get('/dashboard', Index::class)->name('index');
        });
    });
    Route::namespace('App\Http\Livewire\Room')->group(function ()
    {
        Route::name('rooms.')->group(function ()
        {
            Route::prefix('/rooms')->group(function ()
            {
                Route::get('/', Index::class)->name('index');
                Route::get('/show/{room}', Show::class)->name('show');
            });
        });
    });
    Route::namespace('App\Http\Livewire\User')->group(function ()
    {
        Route::name('users.')->group(function ()
        {
            Route::prefix('/users')->group(function ()
            {
                Route::get('/', Index::class)->name('index');
                Route::get('/edit/{user}', \Edit\Index::class)->name('edit');
            });
        });
    });

});
