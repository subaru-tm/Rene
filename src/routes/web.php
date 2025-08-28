<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\MypageController;

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
Auth::routes();

Route::post('/register', [RegisterController::class, 'register']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm']);
Route::get('/login', [LoginController::class, 'loginView']);
Route::post('/login',[LoginController::class, 'login'])->name('login');
Route::get('/thanks', [RegisterController::class, 'thanks'])->name('thanks');

Route::get('/detail/{restaurant_id}', [RestaurantController::class, 'detail'])->name('detail');
Route::get('/search', [RestaurantController::class, 'search']);


Route::middleware('auth')->group(function () {
    Route::post('/cancel/{reservation_id}', [ReservationController::class, 'cancel']);
    Route::post('/detail/{restaurant_id}/reservation', [ReservationController::class, 'reservation']);
    Route::get('/done', [ReservationController::class, 'done']);
    Route::post('/favorite/{restaurant_id}/on', [RestaurantController::class, 'favoriteOn']);
    Route::post('/favorite/{restaurant_id}/off', [RestaurantController::class, 'favoriteOff']);
    Route::get('/mypage', [MypageController::class, 'mypage']);
});


Route::get('/', [RestaurantController::class, 'index'])->name('home');
