<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function reservation(Request $request, $restaurant_id)
    {
        $user_id = Auth::id();
        $number = str_replace('人', '', $request->number);
            // viewの入力欄にある「人」を取り除いてDBに登録。

        $new_reservation = Reservation::create([
            'user_id' => $user_id,
            'restaurant_id' => $restaurant_id,
            'date' => $request->date,
            'time' => $request->time,
            'number' => $number,
        ]);

        return view('/done', compact('restaurant_id'));

    }

    public function done() {
        return view('done');
    }

    public function cancel($reservation_id) {
        $reservation_cancel = Reservation::find($reservation_id)->update([
            'cancel_flug' => '1',
        ]);

        $reservation = Reservation::find($reservation_id);
        $restaurant_id = $reservation->restaurant_id;

        // マイページで予約キャンセルした場合は、マイページに戻るように'url.intended'を設定。
        if( array_key_exists('HTTP_REFERER', $_SERVER)) {
            $path = parse_url($_SERVER['HTTP_REFERER']);
            if( array_key_exists('host', $path)) {
                if( $path['host'] == $_SERVER['HTTP_HOST']) {
                    session(['url.intended' => $_SERVER['HTTP_REFERER']]);
                }
            }
        }

        return redirect()->intended(route('detail', ['restaurant_id' => $restaurant_id]));
    }
}
