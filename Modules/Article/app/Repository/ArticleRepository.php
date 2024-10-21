<?php

namespace Modules\Article\Repository;

use App\Enum\GeneralParamsEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Article\Models\Article;

class ArticleRepository
{
    public function search(array $filter): LengthAwarePaginator
    {
        return Article::query()
            ->when(! empty($filter['title']), function ($query) use ($filter) {
                $query->where('title', 'like', '%'.$filter['title'].'%');
            })
            ->when(! empty($filter['source_id']), function ($query) use ($filter) {
                $query->where('source_id', $filter['source_id']);
            })
            ->when(! empty($filter['category_id']), function ($query) use ($filter) {
                $query->where('category_id', $filter['category_id']);
            })
            ->when(! empty($filter['author_id']), function ($query) use ($filter) {
                $query->where('author_id', $filter['author_id']);
            })
            ->when(! empty($filter['key_words']), function ($query) use ($filter) {
                $query->where('key_words', 'like', '%'.$filter['key_words'].'%');
            })
            ->paginate(GeneralParamsEnum::PAGINATION_LIMIT->value);
    }

    public function show(int $id): Model
    {
        try {
            $article = Article::query()->findOrFail($id)->first();
        } catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }

        return $article;

    }
}
