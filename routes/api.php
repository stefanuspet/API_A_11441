<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);

Route::get('/users', [App\Http\Controllers\Api\UserController::class, 'index']);
Route::post('/users/{id}', [App\Http\Controllers\Api\UserController::class, 'update']);
Route::delete('/users/{id}', [App\Http\Controllers\Api\UserController::class, 'destroy']);
Route::get('/users/{id}', [App\Http\Controllers\Api\UserController::class, 'show']);

Route::group(['middleware' => 'auth:api'], function () {

    Route::get('/contents', [App\Http\Controllers\Api\ContentController::class, 'index']);
    Route::post('/contents', [App\Http\Controllers\Api\ContentController::class, 'store']);
    Route::get('/contents/{id}', [App\Http\Controllers\Api\ContentController::class, 'show']);
    Route::put('/contents/{id}', [App\Http\Controllers\Api\ContentController::class, 'update']);
    Route::delete('/contents/{id}', [App\Http\Controllers\Api\ContentController::class, 'destroy']);

    Route::get('/activities', [App\Http\Controllers\Api\ActivitiesController::class, 'index']);
    Route::post('/activities', [App\Http\Controllers\Api\ActivitiesController::class, 'store']);
    Route::get('/activities/{id}', [App\Http\Controllers\Api\ActivitiesController::class, 'show']);
    Route::put('/activities/{id}', [App\Http\Controllers\Api\ActivitiesController::class, 'update']);
    Route::delete('/activities/{id}', [App\Http\Controllers\Api\ActivitiesController::class, 'destroy']);

    Route::get('/subscriptions', [App\Http\Controllers\Api\SubscriptionsController::class, 'index']);
    Route::post('/subscriptions', [App\Http\Controllers\Api\SubscriptionsController::class, 'store']);
    Route::get('/subscriptions/{id}', [App\Http\Controllers\Api\SubscriptionsController::class, 'show']);
    Route::put('/subscriptions/{id}', [App\Http\Controllers\Api\SubscriptionsController::class, 'update']);
    Route::delete('/subscriptions/{id}', [App\Http\Controllers\Api\SubscriptionsController::class, 'destroy']);
});
