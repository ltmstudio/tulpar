<?php

use App\Http\Controllers\Api\AppController;
use App\Http\Controllers\Api\CarCatalogController;
use App\Http\Controllers\Api\CustomerAddressController;
use App\Http\Controllers\Api\CustomerGeoController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\DriverOrderController;
use App\Http\Controllers\Api\ModerationController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PayController;

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
Route::get('/app/cities', [AppController::class, 'cities']);
Route::get('/app/order-types', [AppController::class, 'orderTypes']);

// User
Route::get('/user', [UserController::class, 'user'])->middleware('auth:sanctum');

// Address
Route::get('/user/address/all', [CustomerAddressController::class, 'getAddresses'])->middleware('auth:sanctum');
Route::post('/user/address/add', [CustomerAddressController::class, 'addAddress'])->middleware('auth:sanctum');
Route::delete('/user/address/delete/{id}', [CustomerAddressController::class, 'deleteAddress'])->middleware('auth:sanctum');

// geo
// Route::post('/customer/geo/geocode', [CustomerGeoController::class, 'fetchLocationName'])->middleware('auth:sanctum');
Route::post('/customer/geo/geocode', [CustomerGeoController::class, 'fetchLocationNameNominatim'])->middleware('auth:sanctum');
Route::post('/customer/geo/route', [CustomerGeoController::class, 'fetchRoute'])->middleware('auth:sanctum');

// Car catalog
Route::get('/catalog/cars', [CarCatalogController::class, 'index']);
Route::get('/catalog/cars/all', [CarCatalogController::class, 'all']);
Route::get('/catalog/cars/{id}', [CarCatalogController::class, 'show']);
Route::get('/catalog/search', [CarCatalogController::class, 'search']);
Route::get('/catalog/car_classes', [CarCatalogController::class, 'carClasses']);

// Auth
Route::post('/auth/phone_to_sms', [UserController::class, 'phoneToSms']);
Route::post('/auth/login', [UserController::class, 'login']);

// Route::get('/auth/google', [UserController::class, 'redirectToGoogle'])->middleware(['web', 'throttle:api']); //web не используется для google йесли на будущее
// Route::get('/auth/google/callback', [UserController::class, 'googleCallback'])->middleware(['web', 'throttle:api']); //web не используется для google йесли на будущее
Route::post('/auth/google/mobile', [UserController::class, 'googleMobileAuth']);

Route::post('/auth/apple/mobile', [UserController::class, 'appleMobileAuth']);

// Moderation
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/driver/moderation', [ModerationController::class, 'index']);
    Route::post('/driver/moderation', [ModerationController::class, 'store']);
    Route::post('/driver/moderation/set', [ModerationController::class, 'setToModeration']);
    Route::post('/driver/moderation/upload_image', [ModerationController::class, 'uploadImage']);
    Route::post('/driver/moderation/delete_image', [ModerationController::class, 'deleteImage']);
});


// Driver
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/driver/profile', [DriverController::class, 'profile']);
    Route::post('/driver/avatar', [DriverController::class, 'uploadImage']);
    Route::delete('/driver/avatar', [DriverController::class, 'deleteImage']);
    Route::get('/driver/level', [DriverController::class, 'level']);
    Route::get('/driver/shifts', [DriverController::class, 'shifts']);
    Route::post('/driver/shifts/{shift_price_id}', [DriverController::class, 'order']);
    Route::get('/driver/shifts_orders', [DriverController::class, 'shiftOrders']);
    Route::get('/driver/shift_status', [DriverController::class, 'shiftStatus']);
});
// Pay
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/pay/info', [PayController::class, 'getPayInfo']);
});

// Driver orders
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/driver/orders', [DriverOrderController::class, 'getNewOrders']);
    Route::get('/driver/orders/{id}', [DriverOrderController::class, 'showOrder']);
    Route::post('/driver/orders/{id}', [DriverOrderController::class, 'takeOrder']);
    Route::post('/driver/order/{id}/close', [DriverOrderController::class, 'closeOrder']);
    Route::post('/driver/order/{id}/cancel', [DriverOrderController::class, 'cancelOrder']);
    Route::get('/driver/my_orders', [DriverOrderController::class, 'myOrders']);
    Route::get('/driver/history_orders', [DriverOrderController::class, 'historyOrders']);
});

// Order
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'create']);
    Route::put('/orders/{id}', [OrderController::class, 'update']);
    Route::delete('/orders/{id}', [OrderController::class, 'destroy']);
});
