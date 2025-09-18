<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Favorite;

class FavoriteFactory extends Factory
{
    /**
     * The name of the factry's corresponding model.
     * 
     * @var string
     */
    protected $model = Favorite::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(1,3),
            'restaurant_id' => $this->faker->unique()->numberBetween(1,20),
            'favorite_flug' => '1',
        ];
    }
}
