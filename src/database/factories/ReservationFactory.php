<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Reservation;
use Carbon\Carbon;

class ReservationFactory extends Factory
{
    /**
     * The name of the factry's corresponding model.
     * 
     * @var string
     */
    protected $model = Reservation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $date = $this->faker->dateTimeBetween('-2 weeks', '3 weeks');
        $now = Carbon::now();
        if( $date < $now ) {
            // 来店済(過去日付)の場合に、評価済のレコードをランダムで生成
            $review_rating = $this->faker->randomElement([
                null,
                $this->faker->numberBetween(1,5)
            ]);

            if( is_null($review_rating) ) {
                // 星評価が未入力(null)なのにコメントのみ入力された状態は、
                // 画面からはあり得ないデータのため、ここでの生成も避ける
                $comment = null;    
            } else {
                $comment = $this->faker->randomElement([
                    null,
                    $this->faker->realText(40)
                ]);
            }
        } else {
            $review_rating = null;
            $comment = null;
        }

        return [
            'user_id' => $this->faker->numberBetween(1,3),
            'restaurant_id' => $this->faker->numberBetween(1,20),
            'date' => $date->format('Y-m-d'),
            'time' => $this->faker->dateTimeBetween('18:00:00', '20:00:00'),
            'number' => $this->faker->numberBetween(1,10),
            'cancel_flug' => '0',
            'review_rating' => $review_rating,
            'comment' => $comment
        ];
    }
}
