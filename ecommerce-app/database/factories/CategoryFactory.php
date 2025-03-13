<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 * 
 * Thinker で $rand = \App\Models\Catefory::factory()->count(10)->create(); するとランダムな商品10個作成されてDBに登録される。
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
            // ユニークなカテゴリー名
            'name' => $this->faker->unique()->word,
        ];
    }
}
