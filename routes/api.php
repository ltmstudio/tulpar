<?php

use App\Http\Controllers\Api\CarCatalogController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Car catalog
Route::get('/catalog/cars', [CarCatalogController::class, 'index']);
Route::get('/catalog/cars/all', [CarCatalogController::class, 'all']);
Route::get('/catalog/cars/{id}', [CarCatalogController::class, 'show']);
Route::get('/catalog/search', [CarCatalogController::class, 'search']);
