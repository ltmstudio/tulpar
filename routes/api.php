<?php

use App\Http\Controllers\Api\AppController;
use App\Http\Controllers\Api\CarCatalogController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\ModerationController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// App
Route::get('/app', [AppController::class, 'index']);
Route::get('/app/localization', [AppController::class, 'localization']);

// User
Route::get('/user', [UserController::class, 'user'])->middleware('auth:sanctum');

// Car catalog
Route::get('/catalog/cars', [CarCatalogController::class, 'index']);
Route::get('/catalog/cars/all', [CarCatalogController::class, 'all']);
Route::get('/catalog/cars/{id}', [CarCatalogController::class, 'show']);
Route::get('/catalog/search', [CarCatalogController::class, 'search']);

// Auth
Route::post('/auth/phone_to_sms', [UserController::class, 'phoneToSms']);
Route::post('/auth/login', [UserController::class, 'login']);

// Moderation
Route::get('/driver/moderation', [ModerationController::class, 'index'])->middleware('auth:sanctum');
Route::post('/driver/moderation', [ModerationController::class, 'store'])->middleware('auth:sanctum');
Route::post('/driver/moderation/set', [ModerationController::class, 'setToModeration'])->middleware('auth:sanctum');
Route::post('/driver/moderation/upload_image', [ModerationController::class, 'uploadImage'])->middleware('auth:sanctum');
Route::post('/driver/moderation/delete_image', [ModerationController::class, 'deleteImage'])->middleware('auth:sanctum');


// Driver
Route::get('/driver/profile', [DriverController::class, 'profile'])->middleware('auth:sanctum');
Route::get('/driver/level', [DriverController::class, 'level'])->middleware('auth:sanctum');
Route::get('/driver/shifts', [DriverController::class, 'shifts'])->middleware('auth:sanctum');
Route::post('/driver/shifts/{shift_price_id}', [DriverController::class, 'order'])->middleware('auth:sanctum');
Route::get('/driver/shift_status', [DriverController::class, 'shiftStatus'])->middleware('auth:sanctum');

