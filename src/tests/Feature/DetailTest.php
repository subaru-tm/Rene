<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Database\Seeders\DatabaseSeeder;
use App\Models\Restaurant;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;

class DetailTest extends TestCase
{
    use RefreshDatabase;
    protected string $seeder = DatabaseSeeder::class;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_displaydetailCheck()
    {
        $restaurant_id = '2'; // 本ケースで使用する店舗ID
        // 飲食店詳細ページを開く
        $response = $this->get("/detail/$restaurant_id");
        $response->assertStatus(200);
        $response->assertViewIs('detail');

        // データベースの店舗名、エリア名、ジャンル名、店舗紹介の各内容と、予約欄の表示を検証
        $databaseData = Restaurant::with('area')->with('genre')->find($restaurant_id);
        $response->assertSee($databaseData->name);
        $response->assertSee($databaseData->area->name);
        $response->assertSee($databaseData->genre->name);
        $response->assertSee($databaseData->description);
        $response->assertSee('予約');
    }

    public function test_entryReservationCheck()
    {
        $restaurant_id = '2'; // 本テストケースで使用する店舗ID
        $restaurant = Restaurant::find($restaurant_id);
            // 検証のために取得しておく
        // ログインする
        $user = User::first();
        $this->actingAs($user)->assertAuthenticated();
        $user_id = Auth::id();

        // 飲食店詳細ページを開く
        $response = $this->get("/detail/$restaurant_id");
        $response->assertStatus(200);

        // 必要項目を入力して「予約する」ボタンを押下する
        $now = Carbon::now();
        $date = $now->addweeks(3)->format('Y/m/d');
        $time = "18:00";
        $number = "5人";
        $response = $this->post(route('reservation', [
            'restaurant_id' => $restaurant_id,
            'date' => $date,
            'time' => $time,
            'number' => $number,
        ]));
        $response->assertStatus(200);

        // 予約完了画面の表示確認し、「戻る」ボタン押下で飲食店詳細画面に戻ることを検証
        $response->assertViewIs('done');
        $response->assertSee('ご予約ありがとうございます');
          // 「戻る」ボタンに相当する<a>タグ内のhref部分を抽出する
        $responseContent = $response->getContent();
        $pattern =  "/href=.*?detail.*?>/i";
        preg_match_all($pattern, $responseContent, $matches);
          // 余分な文字をトリムする
        $link = str_replace('href="', '' , $matches[0]);
        $button_link = str_replace('">', '' , $link[0]);

        // 「戻る」ボタンの押下によるリンク先へ遷移する
        $response = $this->get($button_link);
        $response->assertStatus(200);
        $response->assertSee($restaurant->name);
           // 元の飲食店詳細(店舗IDが2)ページに戻ったことを店舗名の表示で確認

        // データベース(reservations)への保存を検証
           // まず入力時の項目形式からDB保存用の変換を行う
        $database_date = Carbon::parse($date)->format('Y-m-d');
        $database_time = Carbon::parse($time)->format('H:i:s');
        $database_number = str_replace('人', '' , $number);
        $this->assertDatabaseHas('reservations', [
            'user_id' => $user_id,
            'restaurant_id' => $restaurant_id,
            'date' => $database_date,
            'time' => $database_time,
            'number' => $database_number,
            'cancel_flug' => '0',
        ]);
    }

    public function test_cancelReservationCheck()
    {
        // ログインする
        $user = User::first();
        $this->actingAs($user)->assertAuthenticated();
        $user_id = Auth::id();

        // キャンセルする予約を選定(キャンセルされていない予約)
        $reservation = Reservation::where('user_id', $user_id)->where('cancel_flug', '0')->first();
        $reservation_id = $reservation->id;
        $restaurant_id = $reservation->restaurant_id;
        $restaurant = Restaurant::find($restaurant_id);
            // 検証等のために店舗情報も取得。

        // 飲食店詳細ページを開き該当の予約表示を確認
        $response = $this->get("/detail/$restaurant_id");
        $response->assertStatus(200);
        $response->assertSee($restaurant->name);
        $response->assertSee($reservation->date);

        // 「キャンセルする」ボタンの表示を確認
        $expect_cancel_action = "/cancel/$reservation_id";

        $responseContent = $response->getContent();
        $pattern =  "/reservation-card__cancel.*?action=.*?method/i";
        preg_match_all($pattern, $responseContent, $matches);
        $i = 0;
        foreach($matches as $match) {
            $response_cancel_action = str_replace('reservation-card__cancel" action="', "", $match);
            $response_cancel_action = str_replace('" method', '', $response_cancel_action);
            if($response_cancel_action[$i] == $expect_cancel_action) {
                $cancel_action = $response_cancel_action[$i];
            }
            $i++;
        }

        // view表示の中から取得した「キャンセルする」ボタンのactionを実行する
        $response = $this->post($cancel_action);
        $response->assertStatus(302);
        $response->assertRedirect("/detail/$restaurant_id");
          // 元の詳細ページへのリダイレクトを確認

        // データベースへの反映を検証
        $this->assertDatabaseHas('reservations', [
            'id' => $reservation_id,
            'user_id' => $user_id,
            'restaurant_id' => $restaurant_id,
            'cancel_flug' => '1',
        ]);
    }

    public function test_returnIndexCheck() {
        // 「＜」ボタン押下により飲食店一覧ページに遷移することを検証。ログインは不要
        $restaurant_id = '2'; // 本テストケースで使用する店舗ID
        $restaurant = Restaurant::find($restaurant_id);

        // 飲食店詳細ページを開く
        $response = $this->get("/detail/$restaurant_id");
        $response->assertStatus(200);

        // 「＜」ボタンの表示部分のviewのactionを取得
        $responseContent = $response->getContent();
        $pattern =  "/back-button.*?>.*?>/i";
        preg_match($pattern, $responseContent, $match);
        $action = str_replace('back-button" href="', '', $match[0]);
        $action = str_replace('"> ＜ </a>', '', $action);

        // 取得した「＜」ボタンのactionを実行
        $response = $this->get($action);
        $response->assertStatus(200);
        $response->assertViewIs('index');
    }
}
