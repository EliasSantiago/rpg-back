<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GuildController;
use App\Http\Controllers\Api\RpgClassesController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('/register','register');
        Route::post('/login', 'login');
    });

    Route::middleware(['auth:api'])->group(function () {
        Route::controller(AuthController::class)->group(function () {
            Route::get('/information', 'information');
        });

        Route::controller(UserController::class)->group(function () {
            Route::get('/users', [UserController::class, 'index']);
            Route::patch('/users/{userId}', [UserController::class, 'update']);
            Route::post('/users/confirm-all', [UserController::class, 'confirmAll']);
        });

        Route::controller(GuildController::class)->group(function () {
            Route::get('/guilds', [GuildController::class, 'index']);
            Route::post('/guilds', [GuildController::class, 'store']);
            Route::get('/guilds/balance', [GuildController::class, 'balance']);
            Route::post('/guilds/{guildId}/add-user', [GuildController::class, 'addUserToGuild']);
        });

        Route::controller(RpgClassesController::class)->group(function () {
            Route::get('/classes', [RpgClassesController::class, 'index']);
            Route::post('/classes', [RpgClassesController::class, 'store']);
        });
    });
});
