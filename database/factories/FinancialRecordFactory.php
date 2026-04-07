<?php

namespace Database\Factories;

use App\Models\FinancialRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FinancialRecord>
 */
class FinancialRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::inRandomOrder()->first()->id,
            'amount' => fake()->numberBetween(100, 5000),
            'type' => fake()->randomElement(['income', 'expense']),
            'category_id' => \App\Models\Category::inRandomOrder()->first()->id,
            'description' => fake()->sentence(),
            'date' => fake()->date(),
        ];
    }
}
