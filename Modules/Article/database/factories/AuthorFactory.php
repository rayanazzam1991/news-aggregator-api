<?php

namespace Modules\Article\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Article\Enums\AuthorActiveStatusEnum;
use Modules\Article\Models\Author;

/**
 * @extends Factory<Author>
 */
class AuthorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Author::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name,
            'status' => fake()->randomElement(AuthorActiveStatusEnum::values()),
        ];
    }
}
