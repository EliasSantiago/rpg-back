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
            Route::get('/users/{userId}', [UserController::class, 'show']);
            Route::put('/users/{userId}', [UserController::class, 'update']);
            Route::delete('/users/{userId}', [UserController::class, 'delete']);
            Route::post('/users/change-confirmation-all', [UserController::class, 'changeConfirmationAll']);
            Route::patch('/users/change-confirmation/{userId}', [UserController::class, 'changeConfirmation']);
        });

        Route::controller(GuildController::class)->group(function () {
            Route::get('/guilds', [GuildController::class, 'index']);
            Route::get('/guilds/{guildId}', [GuildController::class, 'show']);
            Route::put('/guilds/{guildId}', [GuildController::class, 'update']);
            Route::delete('/guilds/{guildId}', [GuildController::class, 'delete']);
            Route::post('/guilds', [GuildController::class, 'store']);
            Route::post('/guilds/balance', [GuildController::class, 'balance']);
            Route::post('/guilds/{guildId}/add-user', [GuildController::class, 'addUserToGuild']);
            Route::delete('/guilds/{guildId}/users/{userId}', [GuildController::class, 'removeUserFromGuild']);
        });

        Route::controller(RpgClassesController::class)->group(function () {
            Route::get('/classes', [RpgClassesController::class, 'index']);
            Route::post('/classes', [RpgClassesController::class, 'store']);
        });
    });
});
