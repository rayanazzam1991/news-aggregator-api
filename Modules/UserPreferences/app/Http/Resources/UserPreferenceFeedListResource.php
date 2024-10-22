<?php

namespace Modules\UserPreferences\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Article\Models\Author;
use Modules\Article\Models\Category;
use Modules\Article\Models\Source;

/**
 * @OA\Schema(
 *     schema="UserPreferenceFeedListResource",
 *     type="object",
 *     title="User Preference Feed List Resource",
 *     description="Represents a news article based on user preferences",
 *
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID of the article",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="Title of the article",
 *         example="Breaking News: Major Event Happening Now"
 *     ),
 *     @OA\Property(
 *         property="source",
 *         type="string",
 *         description="Name of the article source",
 *         example="New York Times"
 *     ),
 *     @OA\Property(
 *         property="author",
 *         type="string",
 *         description="Name of the author",
 *         nullable=true,
 *         example="John Doe"
 *     ),
 *     @OA\Property(
 *         property="category",
 *         type="string",
 *         description="Category of the article",
 *         nullable=true,
 *         example="World News"
 *     ),
 *     @OA\Property(
 *         property="key_words",
 *         type="array",
 *         description="Array of keywords related to the article",
 *
 *         @OA\Items(
 *             type="string",
 *             example="breaking,world,event"
 *         )
 *     )
 * )
 *
 * @property int $id
 * @property string $title
 * @property Source $source
 * @property Author|null $author
 * @property Category|null $category
 * @property array<string> $key_words
 */
class UserPreferenceFeedListResource extends JsonResource
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
            'title' => $this->title,
            'source' => $this->source->name,
            'author' => $this->author?->name,
            'category' => $this->category?->name,
            'key_words' => $this->key_words,
        ];
    }
}
