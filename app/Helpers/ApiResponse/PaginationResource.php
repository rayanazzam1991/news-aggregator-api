<?php

namespace App\Helpers\ApiResponse;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @OA\Schema(
 *     schema="PaginationResource",
 *     type="object",
 *     title="Pagination Resource",
 *     description="Pagination details for paginated API responses.",
 *
 *     @OA\Property(
 *         property="total",
 *         type="integer",
 *         description="Total number of items available.",
 *         example=100
 *     ),
 *     @OA\Property(
 *         property="count",
 *         type="integer",
 *         description="Number of items on the current page.",
 *         example=10
 *     ),
 *     @OA\Property(
 *         property="per_page",
 *         type="integer",
 *         description="Number of items per page.",
 *         example=10
 *     ),
 *     @OA\Property(
 *         property="page",
 *         type="integer",
 *         description="Current page number.",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="max_page",
 *         type="integer",
 *         description="Maximum number of pages available.",
 *         example=10
 *     )
 * )
 *
 * @method int total()
 * @method int count()
 * @method int perPage()
 * @method int currentPage()
 * @method int lastPage()
 */
class PaginationResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'total' => $this->total(),
            'count' => $this->count(),
            'per_page' => $this->perPage(),
            'page' => $this->currentPage(),
            'max_page' => $this->lastPage(),

        ];

    }
}
