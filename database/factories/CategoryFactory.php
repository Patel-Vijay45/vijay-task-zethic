<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'position' => fake()->numberBetween(0, 10),
            'image' => fake()->imageUrl(200, 200, 'category'),
            'category_banner' => fake()->imageUrl(1200, 300, 'banner'),
            'status' => 1,
            'additional' => json_encode([
                'meta' => fake()->sentence(),
            ]),
        ];
    }
}
