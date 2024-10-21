<?php

namespace Modules\Article\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="CategoriesListResource",
 *     type="object",
 *     title="Categories List Resource",
 *     description="A minimal representation of a category for listing purposes.",
 *
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Unique identifier of the category.",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Name of the category.",
 *         example="Technology"
 *     )
 * )
 *
 * @property int $id
 * @property string $name
 */
class CategoriesListResource extends JsonResource
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
            'name' => $this->name,
        ];
    }
}
