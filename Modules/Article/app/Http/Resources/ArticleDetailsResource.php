<?php

namespace Modules\Article\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ArticleDetailsResource",
 *     type="object",
 *     title="Article Details Resource",
 *     description="Full details of an article",
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
 *         property="key_words",
 *         type="string",
 *         nullable=true,
 *         description="Keywords associated with the article.",
 *         example="AI, Technology, Innovation"
 *     ),
 *     @OA\Property(
 *         property="summary",
 *         type="string",
 *         nullable=true,
 *         description="Short summary of the article.",
 *         example="This article discusses the latest advancements in AI technology."
 *     ),
 *     @OA\Property(
 *         property="image_url",
 *         type="string",
 *         nullable=true,
 *         description="URL of the article's cover image.",
 *         example="https://example.com/images/article1.jpg"
 *     ),
 *     @OA\Property(
 *         property="news_url",
 *         type="string",
 *         nullable=true,
 *         description="URL to the full article.",
 *         example="https://example.com/full-article"
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         nullable=true,
 *         description="Meta information about the article.",
 *         @OA\Property(
 *             property="views",
 *             type="integer",
 *             description="Number of views the article has received.",
 *             example=123
 *         ),
 *         @OA\Property(
 *             property="likes",
 *             type="integer",
 *             description="Number of likes the article has received.",
 *             example=45
 *         )
 *     )
 * )
 * Class ArticleDetailsResource
 *
 * @property int $id
 * @property string $title
 * @property string $source
 * @property string|null $category
 * @property string|null $author
 * @property string|null $key_words
 * @property string|null $summary
 * @property string|null $image_url
 * @property string|null $news_url
 * @property array|null $meta
 * @property string|null $deleted_at
 * @property string $created_at
 * @property string $updated_at
 */
class ArticleDetailsResource extends JsonResource
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
            'category' => $this->category,
            'author' => $this->author,
            'key_words' => $this->key_words,
            'summary' => $this->summary,
            'image_url' => $this->image_url,
            'news_url' => $this->news_url,
            'meta' => $this->meta,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
