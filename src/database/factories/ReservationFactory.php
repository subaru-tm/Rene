<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Reservation;

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
        return [
            'user_id' => $this->faker->numberBetween(1,3),
            'restaurant_id' => $this->faker->numberBetween(1,20),
            'date' => $this->faker->dateTimeBetween('-1 weeks', '4 weeks')->format('Y-m-d'),
            'time' => $this->faker->dateTimeBetween('18:00:00', '20:00:00'),
            'number' =>$this->faker->numberBetween(1,10),
            'cancel_flug' => '0'
        ];
    }
}
