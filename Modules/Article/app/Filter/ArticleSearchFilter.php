<?php

namespace Modules\Article\Filter;

use Spatie\LaravelData\Data;

class ArticleSearchFilter extends Data
{
    /**
     * @param  array<string>|null  $keywords
     */
    public function __construct(
        public ?string $title = null,
        public ?array $keywords = null,
        public ?string $date = null,
        public ?int $category_id = null,
        public ?int $author_id = null,
        public ?int $source_id = null
    ) {}

    /**
     * @param array{
     *     title:string | null,
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
            title: $request['title'] ?? null,
            keywords: $request['keywords'] ?? null,
            date: $request['date'] ?? null,
            category_id: $request['category_id'] ?? null,
            author_id: $request['author_id'] ?? null,
            source_id: $request['source_id'] ?? null,
        );

    }
}
