<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Favorite;

class IndexTest extends TestCase
{
    use RefreshDatabase;
    protected string $seeder = DatabaseSeeder::class;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_displayIndexCheck()
    {
        // ログインしていない状態でも飲食店一覧を参照できる
        $this->assertFalse(Auth::check());
        // 飲食店一覧ページを開く
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('index');

        // responseのデータ部分を抽出し、データベースのrestaurantsテーブル(リレーション付)の全件と一致することを検証
        $responseData = $response->original->getData();
        $databaseData = Restaurant::with('area')->with('genre')->get();
        $this->assertEquals($databaseData, $responseData['restaurants']);
    }

    public function test_displayIndexLoggedInCheck()
    {
        // ログインしていても飲食店一覧を参照できる
        $user = User::first();
        $this->actingAs($user)->assertAuthenticated();
        $user_id = Auth::id();
        $this->assertTrue(Auth::check());
        // 飲食店一覧ページを開く
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('index');

        // responseのデータとデータベースの一致を検証。ログイン済の場合、ログイン中ユーザーが登録したfavoritesも追加となる
        $responseData = $response->original->getData();
        $databaseData = Restaurant::with('area')->with('genre')->with(['favorites' => function ($query) use($user_id) {
                $query->where('favorites.user_id', $user_id);
        }])->get();
        $this->assertEquals($databaseData, $responseData['restaurants']);
    }

    public function test_entryFavoriteCheck()
    {
        // ログインする
        $user = User::first();
        $this->actingAs($user)->assertAuthenticated();
        $user_id = Auth::id();
        $this->assertTrue(Auth::check());

        // 飲食店のうち、お気に入り登録をしていない店舗(ここではfavoritesに未登録)を選ぶ
        $target_restaurant = Restaurant::doesntHave('favorites')->first();
        $target_restaurant_id = $target_restaurant->id;
        // 飲食店一覧ページを開く
        $response = $this->get('/');
        $response->assertStatus(200);
          // 表示されている店舗カードの中に、選んだ店舗のお気に入り登録form(actionで判定)の存在を確認
        $responseContent = $response->getContent();
        $pattern =  "/action=.*?favorite.*?on/i";
        preg_match_all($pattern, $responseContent, $matches);

            // 余分な文字列をトリムする
        foreach($matches as $match) {
            $actions = str_replace('action="', '' , $match);
        }
            // 選んだ店舗にて想定されるaction
        $expect_action = "/favorite/$target_restaurant_id/on";
            // 実際にviewの中に想定されるactionが存在するかチェック
        if( in_array($expect_action, $actions) ) {
            $post_action = $expect_action;
        }

        // formの存在確認したactionでお気に入りを押下する
        $response = $this->post($post_action);
        $response->assertStatus(302);
        // データベースに反映されていることを検証
        $expect_favorite_flug = '1';
        $databaseData = Favorite::where('restaurant_id', $target_restaurant_id)->first();
        $this->assertEquals($expect_favorite_flug, $databaseData->favorite_flug);
    }

    public function test_cancelFavoriteCheck()
    {
        // ログインする
        $user = User::first();
        $this->actingAs($user)->assertAuthenticated();
        $user_id = Auth::id();
        $this->assertTrue(Auth::check());
        // 飲食店のうち、お気に入り登録済の店舗を選ぶ
        $target_favorite_flug = '1';
        $target_favorite = Favorite::where('user_id', $user_id)->where('favorite_flug', $target_favorite_flug)->first();
        $target_restaurant_id = $target_favorite->restaurant_id;

        // 飲食店一覧ページを開く
        $response = $this->get('/');
        $response->assertStatus(200);
          // 表示されている店舗カードの中に、選んだ店舗のお気に入り解除form(actionで判定)の存在を確認
        $responseContent = $response->getContent();
        $pattern =  "/action=.*?favorite.*?off/i";
        preg_match_all($pattern, $responseContent, $matches);

            // 余分な文字列をトリムする
        foreach($matches as $match) {
            $actions = str_replace('action="', '' , $match);
        }
            // 選んだ店舗にて想定されるaction
        $expect_action = "/favorite/$target_restaurant_id/off";
            // 実際にviewの中に想定されるactionが存在するかチェック
        if( in_array($expect_action, $actions) ) {
            $post_action = $expect_action;
        }
        // formの存在確認したactionでお気に入りを押下する
        $response = $this->post($post_action);
        $response->assertStatus(302);
        // データベースに反映されていることを検証
        $expect_favorite_flug = '0';
        $databaseData = Favorite::where('restaurant_id', $target_restaurant_id)->first();
        $this->assertEquals($expect_favorite_flug, $databaseData->favorite_flug);
    }
    /**
     * @test
     * @dataProvider dataproviderSearch
     */
    public function searchCheck(array $keys, array $values, bool $expect)
    {
        $dataList = array_combine($keys, $values);
        // 飲食店一覧ページを開く
        $response = $this->get('/');
        $response->assertStatus(200);
        $response = $this->get(route('search', $dataList));
        $responseData = $response->original->getData();
        // area_idとgenre_idはnullの場合に検索条件から外れるように条件指定。
        // 実装のローカルスコープ条件を疑似反映。nameは部分一致のため考慮不要。
        if( is_null($values[0]) && is_null($values[1]) ) {
            // nameのみの検索(areaとgenreは共にnull)の場合
            $databaseData = Restaurant::with('area')->with('genre')
                ->where($keys[2], 'like', '%' . $values[2] . '%')
                ->get();
        } elseif( is_null($values[0]) ) {
            // areaがnull(少なくともgenreは指定され,nameは任意)の場合
            $databaseData = Restaurant::with('area')->with('genre')
                ->where($keys[1], $values[1])
                ->where($keys[2], 'like', '%' . $values[2] . '%')
                ->get();
        } elseif( is_null($values[1]) ) {
            // genreがnull(少なくともareaは指定され、nameは任意)の場合
            $databaseData = Restaurant::with('area')->with('genre')
                ->where($keys[0], $values[0])
                ->where($keys[2], 'like', '%' . $values[2] . '%')
                ->get();
        } else {
            // area,genreが共に指定されている場合
            $databaseData = Restaurant::with('area')->with('genre')
                ->where($keys[0], $values[0])
                ->where($keys[1], $values[1])
                ->where($keys[2], 'like', '%' . $values[2] . '%')
                ->get();
        }
        $this->assertEquals($databaseData, $responseData['restaurants']);
    }
    public function dataproviderSearch()
    {
        return [
            'areaのみで検索' => [
                ['area_id', 'genre_id', 'name'],
                ['1', null, null],
                true,
            ],
            'genreのみで検索' => [
                ['area_id', 'genre_id', 'name'],
                [null, '1', null],
                true,
            ],
            'nameのみで検索' => [
                ['area_id', 'genre_id', 'name'],
                [null, null, 'ルーク'],
                true,
            ],
            'area,genreで検索' => [
                ['area_id', 'genre_id', 'name'],
                ['2', '2', null],
                true,
            ],
            'area,nameで検索' => [
                ['area_id', 'genre_id', 'name'],
                ['1', null, 'ルーク'],
                true,
            ],
            'genre,nameで検索' => [
                ['area_id', 'genre_id', 'name'],
                [null, '4', 'ルーク'],
                true,
            ],
            'area,genre,nameで検索' => [
                ['area_id', 'genre_id', 'name'],
                ['1', '4', 'ルーク'],
                true,
            ],
        ];
    }

    public function test_moveDetailCheck()
    {
        $restaurant_id = '1';
        // 飲食店一覧ページを開く
        $response = $this->get('/');
        $response->assertStatus(200);
        // 飲食店詳細ページへの遷移ボタンを確認
        $targetUrl = "/detail/$restaurant_id";
        $response->assertSee('詳しくみる');
        $response->assertSee($targetUrl);
        // 飲食店詳細ページへ遷移する
        $response = $this->get($targetUrl);
        $response->assertStatus(200);
        $response->assertViewIs('detail');        
    }
}
