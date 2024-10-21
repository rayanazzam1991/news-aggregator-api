<?php

namespace Modules\Article\Filter;

use Spatie\LaravelData\Data;

class ArticleSearchFilter extends Data
{
    /**
     * @param  array<string>|null  $keywords
     */
    public function __construct(
        public ?array $keywords,
        public ?string $date,
        public ?int $category_id,
        public ?int $author_id,
        public ?int $source_id
    ) {}

    /**
     * @param array{
     *     keywords:array<string> | null,
     *     date:string | null,
     *     category_id:int | null,
     *     source_id:int | null,
     *     author_id:int | null
     * } $request
     */
    public static function fromRequest(array $request): ArticleSearchFilter
    {
        return new self(
            keywords: $request['keywords'] ?? null,
            date: $request['date'] ?? null,
            category_id: $request['category_id'] ?? null,
            author_id: $request['author_id'] ?? null,
            source_id: $request['source_id'] ?? null,
        );

    }
}
