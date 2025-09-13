<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Restaurant;
use App\Models\Area;
use App\Models\Genre;
use App\Mail\NotifyMail;
use Illuminate\Support\Facades\Mail;

class ManagerController extends Controller
{
    public function managerIndex(Request $request) {
        $user = Auth::user();
        $user_id = Auth::id();

        $area_id = $request->area_id;
        $genre_id = $request->genre_id;
        $name = $request->name;

        $restaurantsInChargeAll = Restaurant::with('reservations')->InCharge($user_id)->get();

        $restaurantsInCharge = Restaurant::with('reservations')->AreaSearch($area_id)->GenreSearch($genre_id)->NameSearch($name)->InCharge($user_id)->get();

        $restaurantsOther = Restaurant::AreaSearch($area_id)->GenreSearch($genre_id)->NameSearch($name)->Other($user_id)->get();

        // 店舗代表者の担当店舗の評価平均を算出する
        $i = 0;
        $rating_sum = 0;
        foreach( $restaurantsInChargeAll as $restaurant ) {
            foreach( $restaurant->reservations as $reservation ) {
                $rating_sum = $rating_sum + $reservation->review_rating;
                $i++;
            }
        }

        $rating_average = $rating_sum / $i ;

        $areas = Area::all();
        $genres = Genre::all();

        return view('manager-index', compact('restaurantsInCharge', 'restaurantsOther', 'rating_average', 'areas', 'genres', 'user', 'area_id', 'genre_id', 'name'));
    }

    public function restaurantRegister() {
        $user = Auth::user();
        $areas = Area::all();
        $genres = Genre::all();

        return view('restaurant-register', compact('user', 'areas', 'genres'));
    }

    public function restaurantStore(Request $request) {

        $file = $request->file('img_file');
        $originalName = $file->getClientOriginalName();
        $file->storeAs('public/', $originalName);

        $image_pass = 'storage/' . $originalName;

        $new_restaurant = Restaurant::create([
            'area_id' => $request->area_id,
            'genre_id' => $request->genre_id,
            'user_id' => $request->user_id,
            'name' => $request->name,
            'description' => $request->description,
            'image_pass' => $image_pass,
        ]);

        return redirect('/manager/new/register')->with('status', '登録が完了しました!');
    }

}
