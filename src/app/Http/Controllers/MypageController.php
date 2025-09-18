<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\Favorite;
use App\Models\Area;
use App\Models\Genre;
use Carbon\Carbon;

class MypageController extends Controller
{
    public function mypage() {
        $user = Auth::user();
        $user_id = $user->id;
        $cancel_flug_off = '0';
        $favorite_flug_on = '1';
        $now = Carbon::now();

        $reservations = Reservation::with('restaurant')->MyReservationSearch($user_id, $cancel_flug_off, $now)->get();
        $visitedReservations = Reservation::with('restaurant')->MyVisitedSearch($user_id, $cancel_flug_off, $now)->get();
        $favorites = Favorite::with('restaurant')->MyFavoriteSearch($user_id, $favorite_flug_on)->get();

        $areas = Area::get();
        $genres = Genre::get();

        return view('mypage', compact('user', 'reservations', 'visitedReservations', 'favorites', 'areas' ,'genres'));

    }
}
