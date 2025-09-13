<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 予約入力のバリデーション(ReservationRequest)で使用。
        // 人数入力で未入力（placeholderの「人」のまま）の場合にエラーとする
        Validator::extend('custom_check_number', function ($attribute, $value, $parameters, $validator) { 
            if( $value === '人' ) {
                return false;
            } else {
                return true;
            }
        });
    }
}
