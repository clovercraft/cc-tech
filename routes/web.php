<?php

use App\Http\Controllers\PusherAuthController;
use App\Http\Controllers\DiscordAuthController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::controller(PusherAuthController::class)->group(function () {
    Route::post('/pusher/auth', 'pusher_auth');
});

Route::controller(DiscordAuthController::class)->group(function () {
    Route::get('/oauth/initiate', 'discord_authenticate')->name('discord.authorize');
    Route::get('/oauth/return', 'discord_authorize');
});
