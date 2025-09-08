<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Database\Seeders\DatabaseSeeder;
use App\Models\Favorite;
use App\Models\Reservation;
use App\Models\User;

class MypageTest extends TestCase
{
    use RefreshDatabase;
    protected string $seeder = DatabaseSeeder::class;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_displayMypageCheck()
    {
        // ログインする
        $user = User::first();
        $this->actingAs($user)->assertAuthenticated();
        $user_id = Auth::id();

        // マイページを開く
        $response = $this->get('/mypage');
        $response->assertStatus(200);

        // マイページに表示された予約が、データベースの全ての予約であることを検証
        $responseData = $response->original->getData();
        $databaseReservations = Reservation::with('restaurant')->where('user_id', $user_id)->where('cancel_flug', '0')->get();

        $this->assertEquals($databaseReservations, $responseData['reservations']);

        // マイページに表示されたお気に入り店舗が、データベースの全てのお気に入りであることを検証
        $databaseFavorite = Favorite::with('restaurant')->where('user_id', $user_id)->where('favorite_flug', '1')->get();

        $this->assertEquals($databaseFavorite, $responseData['favorites']);

    }
}
