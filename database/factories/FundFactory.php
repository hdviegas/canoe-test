<?php

namespace Database\Factories;

use App\Models\Fund;
use App\Models\FundManager;
use Illuminate\Database\Eloquent\Factories\Factory;

class FundFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Fund::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'fund_manager_id' => FundManager::factory(),
            'start_year' => $this->faker->year,
            'aliases' => $this->faker->words(3),
        ];
    }
}
