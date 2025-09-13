<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Restaurant;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Reservation;
use App\Models\Favorite;
use Carbon\Carbon;

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
        $now = Carbon::now();

        $reservations = Reservation::with('restaurant')->with('user')->ReservationSearch($user_id, $restaurant_id, $cancel_flug_off, $now)->get();

        $visited_reservations = Reservation::with('restaurant')->with('user')->VisitedSearch($user_id, $restaurant_id, $cancel_flug_off, $now)->get();

        // レビューの平均点を算出する
        $i = 0;
        $rating_sum = 0;
        foreach( $visited_reservations as $reservation ) {
            // 評価済(review_ratingがnullでない)のみ対象
            if( !is_null($reservation->review_rating) ) {
                $rating_sum = $rating_sum + $reservation->review_rating;
                $i++; 
            }
        }

        // 平均点が算出できない(評価がまだない、等)場合を
        // 考慮して、評価の数が0でない場合のみ算出。
        if ( $i <> 0) {
            $rating_average = $rating_sum / $i;
        } else {
            $rating_average = 0;
             // 0を評価がまだない場合と意味付けする
        }

        return view('detail', compact('restaurant', 'reservations', 'visited_reservations', 'rating_average'));
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
