<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param =[
            'name' => 'テストユーザー①',
            'email' => 'test1@test.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('test1pass'),
        ];
        DB::table('users')->insert($param);

        $param =[
            'name' => 'テストユーザー②',
            'email' => 'test2@test.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('test2pass'),
        ];
        DB::table('users')->insert($param);

        $param =[
            'name' => 'テストユーザー③',
            'email' => 'test3@test.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('test3pass'),
        ];
        DB::table('users')->insert($param);

        $param =[
            'name' => 'テストユーザー④',
            'email' => 'test4@test.com',
            'email_verified_at' => Carbon::now(),
            'is_admin' => true,
            'password' => Hash::make('test4pass'),
        ];
        DB::table('users')->insert($param);

        $param =[
            'name' => 'テストユーザー⑤',
            'email' => 'test5@test.com',
            'email_verified_at' => Carbon::now(),
            'is_manager' => true,
            'password' => Hash::make('test5pass'),
        ];
        DB::table('users')->insert($param);
    }
}
