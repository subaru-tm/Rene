<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Livewire\Livewire;

class MenuTest extends TestCase
{
    use RefreshDatabase;

    protected string $seeder = DatabaseSeeder::class;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /**
     * @test
     * @dataProvider dataproviderLoggedIn
     */
    public function LoggedInCheck(array $pages, int $status, string $method, array $texts, string $view, bool $expect)
    {
        $user = User::first();
        $this->actingAs($user)->assertAuthenticated();
        $user_id = Auth::id();

        // メニュー表示前のページ
        $response = $this->get($pages[0]);
        $response->assertStatus($status);

        $this->assertTrue(Auth::check());

        // モーダルウィンドウのメニューを開き、ログイン時のメニュー表示を検証
        Livewire::test('modal')
            ->call($method)
            ->assertSee($texts[0])
            ->assertSee($texts[1])
            ->assertSee($texts[2]);
        // メニューでのボタン押下
        $response = $this->get($pages[1]);
        $response->assertStatus($status);
        $response->assertViewIs($view);
    }

    public function dataproviderLoggedIn()
    {
        return [
            'ログイン状態でのメニュー表示を検証' => [
                ['/detail/2', '/detail/2'],  // 当ケースでは画面遷移しない
                200,
                'openModal',
                ['Home', 'Logout', 'Mypage'],
                'detail',
                true
            ],

            'ログイン後のHomeボタン押下を検証' => [
                ['/detail/2', '/'],
                200,
                'openModal',
                ['Home', 'Logout', 'Mypage'],
                'index',
                true
            ],

            'ログイン後のMypageボタン押下を検証' => [
                ['/detail/2', '/mypage'],
                200,
                'openModal',
                ['Home', 'Logout', 'Mypage'],
                'mypage',
                true
            ],
        ];
    }

    public function test_closeModalCheck()
    {
        // ログインする
        $user = User::first();
        $this->actingAs($user)->assertAuthenticated();
        $user_id = Auth::id();

        $response = $this->get('/detail/2');
        $response->assertStatus(200);

        $this->assertTrue(Auth::check());

        // モーダルウィンドウのメニューを開く
        Livewire::test('modal')
            ->call('openModal');

        // モーダルウィンドウのメニューを閉じる
        Livewire::test('modal')
            ->call('closeModal')
            ->assertDontSee('Home')
            ->assertDontSee('Logout')
            ->assertDontSee('Mypage');
    }

    public function test_logoutCheck()
    {
        // ログインする
        $user = User::first();
        $this->actingAs($user)->assertAuthenticated();
        $user_id = Auth::id();

        $response = $this->get('/detail/2');
        $response->assertStatus(200);
        $this->assertTrue(Auth::check());

        // モーダルウィンドウのメニューを開く
        Livewire::test('modal')
            ->call('openModal')
            ->assertSee('Logout');

        $response = $this->post('/logout');
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertFalse(Auth::check());
    }

    /**
     * @test
     * @dataProvider dataproviderDontLogin
     */
    public function DontLoginCheck(array $pages, int $status, string $method, array $texts, string $view, bool $expect)
    {
        // ログインしていない状態でのメニューを検証する
        $response = $this->get($pages[0]);
        $this->assertFalse(Auth::check());

        // モーダルウィンドウのメニューを開き、ログイン時のメニュー表示を検証
        Livewire::test('modal')
            ->call($method)
            ->assertSee($texts[0])
            ->assertSee($texts[1])
            ->assertSee($texts[2]);
        // メニューでのボタン押下
        $response = $this->get($pages[1]);
        $response->assertStatus($status);
        $response->assertViewIs($view);
    }

    public function dataproviderDontLogin()
    {
        return [
            '非認証(ログインしない)状態でのメニュー表示を検証' => [
                ['/', '/'],  // 当ケースでは画面遷移しない
                200,
                'openModal',
                ['Home', 'Registration', 'Login'],
                'index',
                true
            ],

            '非認証状態でのHomボタン押下を検証' => [
                ['/detail/2', '/'],
                200,
                'openModal',
                ['Home', 'Registration', 'Login'],
                'index',
                true
            ],

            '非認証状態でのRegstrationボタン押下を検証' => [
                ['/', '/register'],
                200,
                'openModal',
                ['Home', 'Registration', 'Login'],
                'auth.register',
                true
            ],

            '非認証状態でのLoginボタン押下を検証' => [
                ['/', '/login'],
                200,
                'openModal',
                ['Home', 'Registration', 'Login'],
                'auth.login',
                true
            ],
        ];
    }

    public function test_closeModalDontLoginCheck()
    {
        $response = $this->get('/');
        $this->assertFalse(Auth::check());

        // モーダルウィンドウのメニューを開く
        Livewire::test('modal')
            ->call('openModal');

        // モーダルウィンドウのメニューを閉じる
        Livewire::test('modal')
            ->call('closeModal')
            ->assertDontSee('Home')
            ->assertDontSee('Registration')
            ->assertDontSee('Login');
    }
}
