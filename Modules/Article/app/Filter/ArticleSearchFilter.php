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
        public ?string $category,
        public ?string $author,
        public ?string $source
    ) {}

    /**
     * @param array{
     *     keywords:array<string> | null,
     *     date:string | null,
     *     category:string | null,
     *     source:string | null,
     *     author:string | null
     * } $request
     */
    public static function fromRequest(array $request): ArticleSearchFilter
    {
        return new self(
            keywords: $request['keywords'] ?? null,
            date: $request['date'] ?? null,
            category: $request['category'] ?? null,
            author: $request['author'] ?? null,
            source: $request['source'] ?? null,
        );

    }
}
