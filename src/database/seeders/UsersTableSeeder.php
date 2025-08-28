<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
            'password' => Hash::make('test1pass'),
        ];
        DB::table('users')->insert($param);

        $param =[
            'name' => 'テストユーザー②',
            'email' => 'test2@test.com',
            'password' => Hash::make('test2pass'),
        ];
        DB::table('users')->insert($param);
    }
}
