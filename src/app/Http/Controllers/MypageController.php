<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\Favorite;
use App\Models\Area;
use App\Models\Genre;

class MypageController extends Controller
{
    public function mypage() {
        $user = Auth::user();
        $user_id = $user->id;
        $cancel_flug_off = '0';
        $favorite_flug_on = '1';

        $reservations = Reservation::with('restaurant')->MyReservationSearch($user_id, $cancel_flug_off)->get();
        $favorites = Favorite::with('restaurant')->MyFavoriteSearch($user_id, $favorite_flug_on)->get();

        $areas = Area::get();
        $genres = Genre::get();

        return view('mypage', compact('user', 'reservations', 'favorites', 'areas' ,'genres'));

    }
}
