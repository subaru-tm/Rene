<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Restaurant;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Reservation;
use App\Models\User;
use App\Mail\NotifyMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\RestaurantRegisterRequest;
use Carbon\Carbon;

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

    public function restaurantStore(RestaurantRegisterRequest $request) {

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

    public function restaurantEdit($restaurant_id) {
        $restaurant = Restaurant::with('user')->find($restaurant_id);
        $areas = Area::all();
        $genres = Genre::all();

        return view('restaurant-edit', compact('restaurant', 'areas', 'genres'));
    }

    public function restaurantUpdate(Request $request, $restaurant_id) {

        if( !is_null($request->file('img_file')) ) {
            $file = $request->file('img_file');
            $originalName = $file->getClientOriginalName();
            $image_pass = 'storage/' . $originalName;

            if( asset($image_pass) ) {
                // 既に登録済のファイルの場合、storageに保存しない
            } else {
                $file->storeAs('public/', $originalName);
            }
        } else {
            // img_fileが送信されていない(null)場合、店舗画像は既存DB値のまま
            $restaurant = Restaurant::find($restaurant_id);
            $image_pass = $restaurant->image_pass;
        }

        $restaurant = Restaurant::find($restaurant_id)->update([
            'area_id' => $request->area_id,
            'genre_id' => $request->genre_id,
            'user_id' => $request->user_id,
            'name' => $request->name,
            'description' => $request->description,
            'image_pass' => $image_pass,
        ]);

        return redirect()->route('restaurant.edit', compact('restaurant_id'))->with('status', '更新が完了しました!');
    }

    public function reservationStatus($restaurant_id) {

        $now = Carbon::now();
        $cancel_flug_off = '0';

        $reservations = Reservation::with('restaurant')
            ->with('user')
            ->RestaurantIdSearch($restaurant_id, $cancel_flug_off, $now)
            ->get();

        $restaurant = Restaurant::find($restaurant_id);

        return view('reservation-check', compact('reservations','restaurant'));
    }

    public function managerNotify($restaurant_id, $user_id) {

        if( !is_null($restaurant_id) && !is_null($user_id))
        {
            $restaurant = Restaurant::find($restaurant_id);
            $user = User::find($user_id);
            return view('manager-notify', compact('restaurant', 'user'));
        } elseif ( !is_null($restaurant_id) && is_null($user_id) )
        {
            $restaurant = Restaurant::find($restaurant_id);
            return view('manager-notify', compact('restaurant'));
        } elseif ( !is_null($user_id) && is_null($restaurant_id) )
        {
            // 現状は、店舗指定が無いのにユーザー(客)が特定されている
            // 当ケースは想定外だが、論理的なMECEのために記述。
            $user = User::find($user_id);
            return view('manager-notify', compact('user'));
        } else {
            return view('manager-notify');
        }

    }

    public function sendNotify(Request $request) {

        $now = Carbon::now();
        $cancel_flug_off = '0';

        switch ($request->to) {
            case 'individual':
                $user_id = $request->user_id;
                $user = User::find($user_id);
                $restaurant_id = $request->restaurant_id;
                break;
            case 'visited':
                $restaurant_id = $request->restaurant_id;
                $visited_reservations = Reservation::with('user')->RestaurantIdVisitedSearch($restaurant_id, $cancel_flug_off, $now)->get();

                $users_ids = $visited_reservations->pluck('user_id');
                $users = User::whereIn('id', $users_ids)->get();

                $user_id = '0'; // 個人宛用変数は初期化
                break;
            case 'all':
                $users = User::all();
                $restaurant_id = $request->restaurant_id;

                $user_id = '0'; // 個人宛用変数は初期化    
                break;
        }

        $subject = $request->subject;
        $content = $request->content;

        // お知らせのメール送信を実行。
        // 個人宛と複数人送付(foreachで繰り返す)で区別
        if ( $request->to == 'individual')
        {
            Mail::to($user->email)->send(new NotifyMail($subject, $content));            
        } else
        {
            $users_emails = [];

            foreach( $users as $user ) {
                $users_emails[] =  $user->email;
            }

            Mail::to($users_emails)->send(new NotifyMail($subject, $content));
            // 複数人送付の場合、本来はbccで送るべきと考えましたが、
            // mailtrapプランの都合、アップグレードしないと
            // BCC宛先確認ができないようです。
            // このため、検証のためとして複数人送付でもtoとしています。

        }

        return redirect()->route('manager.notify', [
            'restaurant_id' => $restaurant_id,
            'user_id' => $user_id,
        ])->with('status', '送信が完了しました!');
    }
}
