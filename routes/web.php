<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TrackingController;

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

// Route::get('/home', function() {
//     return redirect()->route('manage');
// });

Route::get('/user_manage', [HomeController::class, 'user_manage'])->name('manage');
Route::post('/user_manage/add_device', [HomeController::class, 'add_device']);

Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking');
Route::post('/tracking/date_changed', [TrackingController::class, 'dateChanged']);