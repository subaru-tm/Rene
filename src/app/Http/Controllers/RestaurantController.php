<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Restaurant;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Reservation;
use App\Models\Favorite;

class RestaurantController extends Controller
{
    public function index() {
        $areas = Area::get();
        $genres = Genre::get();

        $restaurants = new Restaurant();

        if(Auth::check()) {
            // ログイン済の場合、ログイン中ユーザーのお気に入り情報もリレーションで取得する
            $user_id = Auth::id();
            $restaurants = Restaurant::with('area')->with('genre')->with(['favorites' => function ($query) use($user_id) {
                $query->where('favorites.user_id', $user_id);
            }])->get();

        } else {
            $restaurants = Restaurant::with('area')->with('genre')->get();
        }

        return view('index', compact('restaurants', 'areas', 'genres'));

    }

    public function search(Request $request)
    {
        $area_id = $request->area_id;
        $genre_id = $request->genre_id;
        $name = $request->name;

        $restaurants = Restaurant::with('area')->with('genre')->AreaSearch($area_id)->GenreSearch($genre_id)->NameSearch($name)->get();

        $areas = Area::get();
        $genres = Genre::get();

        return view('index', compact('restaurants', 'areas', 'genres', 'area_id', 'genre_id', 'name'));
    }

    public function detail($restaurant_id) {
        $user_id = Auth::id();
        $restaurant = Restaurant::find($restaurant_id);

        $cancel_flug_off = '0';
        $reservations = Reservation::with('restaurant')->ReservationSearch($user_id, $restaurant_id, $cancel_flug_off)->get();

        return view('detail', compact('restaurant', 'reservations'));
    }

    public function favoriteOn($restaurant_id) {
        $user_id = Auth::id();
        $favorite = Favorite::updateOrCreate(
            ['user_id' => $user_id, 'restaurant_id' => $restaurant_id],
            ['favorite_flug' => '1'],
        );

        return redirect('/');

    }

    public function favoriteOff($restaurant_id) {
        $user_id = Auth::id();
        $favorite = Favorite::where('user_id', $user_id)
            ->where('restaurant_id', $restaurant_id)
            ->update(['favorite_flug' => '0']);

        // マイページでお気に入り解除した場合は、マイページに戻るように'url.intended'を設定。
        if( array_key_exists('HTTP_REFERER', $_SERVER)) {
            $path = parse_url($_SERVER['HTTP_REFERER']);
            if( array_key_exists('host', $path)) {
                if( $path['host'] == $_SERVER['HTTP_HOST']) {
                    session(['url.intended' => $_SERVER['HTTP_REFERER']]);
                }
            }
        }

        return redirect()->intended('/');
    }

}
