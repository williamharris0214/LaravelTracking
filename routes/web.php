<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\UserController;

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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();
Route::group(['middleware' => 'auth'], function() {
    Route::get('/user_manage', [UserController::class, 'user_manage'])->name('manage');
    Route::post('/user_manage/add_device', [UserController::class, 'add_device']);
    
    Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking');
    Route::post('/tracking/date_changed', [TrackingController::class, 'dateChanged']);
});