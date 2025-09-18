<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\RegisterRequest;
use Database\Seeders\DatabaseSeeder;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    protected string $seeder = DatabaseSeeder::class;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_registerNormalCheck() {
        // 1-1.フォームに内容が正常入力された場合、データが正常に保存される
        $response = $this->post('/register', [
            'name' => 'testuser',
            'email' => 'test@test.com',
            'password' => 'testpass',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('thanks'));
        $this->assertDatabaseHas('users', [
            'name' => 'testuser',
            'email' => 'test@test.com',
        ]);
    }

    /**
     * @test
     * @dataProvider dataproviderValidation
     */
    public function registerValidationCheck(array $keys, array $values, array $messages, bool $expect)
    {
        $dataList = array_combine($keys, $values);

        $request = new RegisterRequest;
        $rules = $request->rules();
        $validator = Validator::make($dataList, $rules);
        $validator = $validator->setCustomMessages($request->messages());
        $result = $validator->passes();
        $this->assertEquals($expect, $result);
        $this->assertSame($messages, $validator->errors()->messages());

    }

    public function dataproviderValidation()
    {
        return [
            '1-2. 名前が未入力の場合、「お名前を入力してください」を表示' => [
                ['name', 'email', 'password'],
                [null, 'test@test.com', 'testpass'],
                ['name' => ['お名前を入力してください']],
                false
            ],
            '1-3. メールアドレスが未入力の場合、「メールアドレスを入力してください」を表示' => [
                ['name', 'email', 'password'],
                ['testuser', null, 'testpass'],
                ['email' => ['メールアドレスを入力してください']],
                false
            ],
            '1-4. パスワードが未入力の場合、「パスワードを入力してください」を表示' => [
                ['name', 'email', 'password'],
                ['testuser', 'test@test.com', null],
                ['password' => ['パスワードを入力してください']],
                false
            ],
            '1-5. メールアドレスの入力規則違反の場合、「メールアドレスは @を含むメール形式で入力してください」を表示' => [
                ['name', 'email', 'password'],
                ['testuser', 'testtest.com', 'testpass'],
                ['email' => ['メールアドレスは @を含むメール形式で入力してください']],
                false
            ],
            '1-6. メールアドレスが登録済の場合、「既に登録済のメールアドレスです」を表示' => [
                ['name', 'email', 'password'],
                ['testuser', 'test1@test.com', 'testpass'],
                ['email' => ['既に登録済のメールアドレスです']],
                false
            ],
            '1-7. パスワードが8文字未満の場合、「パスワードは8文字以上で入力してください」を表示' => [
                ['name', 'email', 'password'],
                ['testuser', 'test@test.com', 'test'],
                ['password' => ['パスワードは8文字以上で入力してください']],
                false
            ],
        ];
    }
}
