<?php

namespace Modules\UserPreferences\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="UserPreferencesListResource",
 *     type="object",
 *     title="User Preferences List Resource",
 *     description="Represents a user preference with type and value",
 *
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID of the preference",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="preference_type_name",
 *         type="string",
 *         description="Type name of the preference",
 *         example="category"
 *     ),
 *     @OA\Property(
 *         property="preference_value",
 *         type="string",
 *         description="Value of the preference",
 *         example="Technology"
 *     )
 * )
 *
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
            'preference_value' => $this->preferenceValue,
        ];
    }
}
