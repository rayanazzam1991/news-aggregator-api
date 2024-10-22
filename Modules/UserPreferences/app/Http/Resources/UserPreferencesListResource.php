<?php

namespace Modules\UserPreferences\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $preferenceType
 * @property string $preferenceValue
 */
class UserPreferencesListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'preference_type_name' => $this->preferenceType,
            'preference_value' => $this->preferenceValue
        ];
    }
}
