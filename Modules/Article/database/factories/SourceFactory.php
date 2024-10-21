<?php

namespace Modules\Article\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Article\Enums\SourceActiveStatusEnum;
use Modules\Article\Models\Source;

/**
 * @extends Factory<Source>
 */
class SourceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Source::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name,
            'status' => fake()->randomElement(SourceActiveStatusEnum::values()),
        ];
    }
}
