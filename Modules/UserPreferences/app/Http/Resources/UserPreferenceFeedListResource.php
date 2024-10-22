<?php

namespace Modules\UserPreferences\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Article\Models\Author;
use Modules\Article\Models\Category;
use Modules\Article\Models\Source;

/**
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
