<?php

namespace Modules\Article\Repository;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Article\Models\Article;

class ArticleRepository
{
    public function search(array $filter)
    {
        return Article::query()
            ->when(! empty($filter['title']), function ($query) use ($filter) {
                $query->where('title', 'like', '%'.$filter['title'].'%');
            })
            ->when(! empty($filter['source']), function ($query) use ($filter) {
                $query->where('source', 'like', '%'.$filter['source'].'%');
            })
            ->when(! empty($filter['category']), function ($query) use ($filter) {
                $query->where('category', 'like', '%'.$filter['category'].'%');
            })
            ->when(! empty($filter['author']), function ($query) use ($filter) {
                $query->where('author', 'like', '%'.$filter['author'].'%');
            })
            ->when(! empty($filter['key_words']), function ($query) use ($filter) {
                $query->where('key_words', 'like', '%'.$filter['key_words'].'%');
            })
            ->paginate(100);
    }

    public function show(int $id)
    {
        try {
            $article = Article::query()->findOrFail($id)->first();
        } catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }

        return $article;

    }
}
