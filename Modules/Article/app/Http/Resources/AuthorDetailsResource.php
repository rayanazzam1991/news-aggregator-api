<?php

namespace Modules\Article\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="AuthorDetailsResource",
 *     type="object",
 *     title="Author Details Resource",
 *     description="Full details of an author.",
 *
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Unique identifier of the author.",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Name of the author.",
 *         example="John Doe"
 *     )
 * )
 *
 * @property int $id
 * @property string $name
 */
class AuthorDetailsResource extends JsonResource
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
