<?php

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    // 対象モデルを指定
    protected $model = Tag::class;

    public function definition(): array
    {
        return [
            // Faker を利用してユニークな単語を生成
            'name' => $this->faker->unique()->word,
        ];
    }
}
