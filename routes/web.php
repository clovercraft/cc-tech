<?php

use App\Http\Controllers\PusherAuthController;
use App\Http\Controllers\DiscordAuthController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\MinecraftApiController;
use Illuminate\Support\Facades\Artisan;
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

Route::controller(FrontController::class)
    ->name('front.')
    ->group(function () {
        Route::get('/', 'index')->name('home');
    });

Route::controller(PusherAuthController::class)->group(function () {
    Route::post('/pusher/auth', 'pusher_auth');
});

Route::controller(DiscordAuthController::class)->group(function () {
    Route::get('/oauth/initiate', 'discord_authenticate')->name('discord.authorize');
    Route::get('/oauth/return', 'discord_authorize');

    Route::get('/oauth/member/initiate', 'discord_member_auth')->name('discord.authorize-member');
    Route::get('/oauth/member/return', 'discord_member_authorize')->name('discord.redirect-member');
});

Route::controller(MinecraftApiController::class)->group(function () {
    Route::post('/mcevents/log', 'event_hook');
});
