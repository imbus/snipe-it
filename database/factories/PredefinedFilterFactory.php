<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PredefinedFilter>
 */
class PredefinedFilterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       return [
            'name' => "Category I Dont know",
            'created_by' => User::factory(),
            'category_id' => 2,
            // 'category_id' => Category::factory()->assetLaptopCategory(),
        ];
    }
}
