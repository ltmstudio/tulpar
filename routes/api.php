<?php

use App\Http\Controllers\Api\CarCatalogController;
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
Route::post('/driver/moderation', [ModerationController::class, 'store'])->middleware('auth:sanctum');
Route::post('/driver/moderation/upload_image', [ModerationController::class, 'uploadImage'])->middleware('auth:sanctum');

