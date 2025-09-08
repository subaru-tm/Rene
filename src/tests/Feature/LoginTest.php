<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected string $seeder = DatabaseSeeder::class;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_loginNormalCheck()
    {
        // 本テストケース用のテストユーザーを作成。
        $user = User::create([
            'name' => 'testuser',
            'email' => 'test@test.com',
            'password' => Hash::make('testpass'),
        ]);

        // ログイン直前にいたページを保存する
        // ログイン後にこのページにリダイレクトされることを検証するため。
        $intendedPath = '/detail/1';
        $this->withSession(['url.intended' => $intendedPath]);

        // ログインページを開き、全ての必要項目を入力の上、ログインボタンを押す
        $response = $this->post('/login', [
            'email' => 'test@test.com',
            'password' => 'testpass',
        ]);

        $response->assertStatus(302);

        // ログイン直前のページに遷移し、ログインされていることを検証。
        $response->assertRedirect($intendedPath);
        $this->assertTrue(Auth::check());

    }

    
}
