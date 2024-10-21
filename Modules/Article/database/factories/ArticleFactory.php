<?php

namespace Modules\Article\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Article\Models\Article;
use Modules\Article\Models\Author;
use Modules\Article\Models\Category;
use Modules\Article\Models\Source;

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
            'source_id' => fn () => Source::query()->first()
                ? Source::query()->first()->id
                : Source::factory()->create()->id,
            'category_id' => fn () => Category::query()->first()
                ? Category::query()->first()->id
                : Category::factory()->create()->id,
            'author_id' => fn () => Author::query()->first()
                ? Author::query()->first()->id
                : Author::factory()->create()->id,
            'key_words' => implode(',', fake()->words(5)),
            'summary' => fake()->sentence,
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
