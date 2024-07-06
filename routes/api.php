<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrganisationController;
use App\Http\Controllers\UserController;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::get('/organisations', [OrganisationController::class, 'index']);
    Route::get('/organisations/{id}', [OrganisationController::class, 'show']); // Update to use integer ID
    Route::post('/organisations', [OrganisationController::class, 'store']);
    Route::post('/organisations/{id}/users', [OrganisationController::class, 'addUser']); // Update to use integer ID
});
