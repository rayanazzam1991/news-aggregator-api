<?php

namespace Modules\Article\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ArticlesListResource",
 *     type="object",
 *     title="Articles List Resource",
 *     description="Minimal details of articles in the list view",
 *
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Unique identifier of the article.",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="Title of the article.",
 *         example="Breaking News: Major Update in Technology"
 *     ),
 *     @OA\Property(
 *         property="source",
 *         type="string",
 *         description="Source of the article.",
 *         example="TechCrunch"
 *     ),
 *     @OA\Property(
 *         property="category",
 *         type="string",
 *         nullable=true,
 *         description="Category of the article.",
 *         example="Technology"
 *     ),
 *     @OA\Property(
 *         property="author",
 *         type="string",
 *         nullable=true,
 *         description="Author of the article.",
 *         example="John Doe"
 *     ),
 *     @OA\Property(
 *         property="summary",
 *         type="string",
 *         nullable=true,
 *         description="Short summary of the article.",
 *         example="This article discusses the latest advancements in AI technology."
 *     )
 * )
 * Class ArticlesListResource
 *
 * @property int $id
 * @property string $title
 * @property string $source
 * @property string $author
 * @property string $category
 * @property array<string> $key_words
 */
class ArticlesListResource extends JsonResource
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
            'source' => $this->source,
            'author' => $this->author,
            'category' => $this->category,
            'key_words' => $this->key_words,
        ];
    }
}
