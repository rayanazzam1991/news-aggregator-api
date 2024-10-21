<?php

namespace Modules\Article\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Article\Models\Article;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence,
            'source' => fake()->company,
            'category' => fake()->word,
            'author' => fake()->name,
            'key_words' => implode(',', fake()->words(5)),
            'summary' => fake()->paragraph,
            'image_url' => fake()->imageUrl,
            'news_url' => fake()->url,
            'meta' => json_encode([
                'views' => fake()->numberBetween(100, 5000),
                'likes' => fake()->numberBetween(10, 1000),
            ]),
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ];
    }
}
