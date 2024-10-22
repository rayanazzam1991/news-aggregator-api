<?php

namespace Modules\UserPreferences\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Article\Enums\PreferenceTypesEnum;
use Modules\UserPreferences\Models\UserPreference;

/**
 * @extends Factory<UserPreference>
 */
class UserPreferenceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = UserPreference::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => fake()->randomNumber(),
            'preference_id' => fake()->randomNumber(nbDigits: 2),
            'preference_type' => fake()->randomElement(PreferenceTypesEnum::values()),
        ];
    }
}
