<?php

use App\Livewire\AccessComponent;
use App\Livewire\CarClassComponent;
use App\Livewire\CarComponent;
use App\Livewire\CitiesComponent;
use App\Livewire\DriverLevelComponent;
use App\Livewire\DriversComponent;
use App\Livewire\HomeComponent;
use App\Livewire\ModerationComponent;
use App\Livewire\PricesComponent;
use App\Livewire\ShiftsComponent;
use App\Livewire\TranslationsComponent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::get('/', HomeComponent::class)->middleware('auth');
Route::get('/index', function () {
    return redirect('/');
})->middleware('auth');

Route::get('/access', AccessComponent::class)->middleware('admin');
Route::get('/drivers', DriversComponent::class)->middleware('manager');
Route::get('/moderation', ModerationComponent::class)->middleware('admin');
Route::get('/car_classes', CarClassComponent::class)->middleware('manager');
Route::get('/cars', CarComponent::class)->middleware('admin');
Route::get('/driver_levels', DriverLevelComponent::class)->middleware('manager');
Route::get('/prices', PricesComponent::class)->middleware('admin');
Route::get('/cities', CitiesComponent::class)->middleware('admin');
Route::get('/shifts', ShiftsComponent::class)->middleware('admin');
Route::get('/translations', TranslationsComponent::class)->middleware('admin');

Route::get('/file', [App\Http\Controllers\FileController::class, 'getFile'])->middleware('storage_access');