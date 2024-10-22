<?php

namespace Modules\UserPreferences\DTO;

use Spatie\LaravelData\Data;

class StoreUserPreferenceDTO extends Data
{
    public function __construct(
        public int $user_id,
        public int $preference_id,
        public string $preference_type,
    ) {}

    public static function fromRequest(array $request): StoreUserPreferenceDTO
    {
        return new self(
            user_id: $request['user_id'],
            preference_id: $request['preference_id'],
            preference_type: $request['preference_type']
        );
    }
}
