<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\Auth\VerificationController;
use Laravel\Fortify\Http\Controllers\VerifyEmailController;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\EmailVerificationRequest;

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
Auth::routes(['verify' => true]); // メール認証を適用

Route::get('/logout', [LoginController::class, 'destroy'])->name('logout');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm']);
Route::post('/login/store',[LoginController::class, 'login'])->name('login.store');
Route::get('/login', [LoginController::class, 'loginView'])->name('login');
Route::get('/thanks', [RegisterController::class, 'thanks'])->name('thanks');

Route::get('/detail/{restaurant_id}', [RestaurantController::class, 'detail'])->name('detail');
Route::get('/search', [RestaurantController::class, 'search'])->name('search');


Route::middleware('auth', 'verified')->group(function () {
    Route::patch('/review/{reservation_id}', [ReservationController::class, 'review']);
    Route::patch('/update/{reservation_id}', [ReservationController::class, 'update']);
    Route::post('/cancel/{reservation_id}', [ReservationController::class, 'cancel']);
    Route::post('/detail/{restaurant_id}/reservation', [ReservationController::class, 'reservation'])->name('reservation');
    Route::get('/stripe/index', [ReservationController::class, 'stripe'])->name('stripe');
 
    Route::get('/done', [ReservationController::class, 'done']);
        Route::post('/favorite/{restaurant_id}/on', [RestaurantController::class, 'favoriteOn'])->name('favorite.on');
    Route::post('/favorite/{restaurant_id}/off', [RestaurantController::class, 'favoriteOff'])->name('favorite.off');
    Route::get('/mypage', [MypageController::class, 'mypage']);
});


// 追加実装の管理画面
Route::middleware('auth', 'verified')->group(function () {
    Route::patch('/admin/empowerment/{user_id}', [AdminController::class, 'empowerment']);
    Route::patch('/admin/revoke/{user_id}', [AdminController::class, 'revoke']);
    Route::post('/admin/notify/send', [AdminController::class, 'sendNotify']);
    Route::get('/admin/notify', [AdminController::class, 'adminNotify']);
    Route::get('/admin/index/search', [AdminController::class, 'adminSearch']);
    Route::get('/admin/index', [AdminController::class, 'adminIndex']);

    Route::get('/manager/{restaurant_id}/notify/{user_id}', [ManagerController::class, 'managerNotify'])->name('manager.notify');
    Route::get('/manager/{restaurant_id}/reservation/check', [ManagerController::class, 'reservationStatus']);
    Route::post('/manager/notify/send', [ManagerController::class, 'sendNotify']);

    Route::patch('/manager/{restaurant_id}/update', [ManagerController::class, 'restaurantUpdate']);
    Route::get('/manager/{restaurant_id}/edit', [ManagerController::class, 'restaurantEdit'])->name('restaurant.edit');
    Route::post('/manager/new/register/store', [ManagerController::class, 'restaurantStore']);
    Route::get('/manager/new/register', [ManagerController::class, 'restaurantRegister']);

    Route::get('/manager/index/search', [ManagerController::class, 'managerIndex']);
    Route::get('/manager/index', [ManagerController::class, 'managerIndex']);
});

// 追加実装のメール認証
// Route::get('/verify', [VerificationController::class, 'verify'])->name('verify'); //メール認証確認画面

Route::get('/email/verify', function(Request $request) {
//    dd(session()->get('unauthenticated_user'));
    return view('auth.verify');
})->name('verification.notice');

// Route::post('/email/verification-notification', function (Request $request) {
//    session()->get('unauthenticated_user')->sendEmailVerificationNotification();
//    session()->put('resent', true);
//    return back()->with('message', 'Verification link sent!');
// })->name('verification.send');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    session()->forget('unauthenticated_user');
    return redirect()->route('home');
})->name('verification.verify');


Route::get('/', [RestaurantController::class, 'index'])->name('home');
