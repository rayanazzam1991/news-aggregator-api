<?php

namespace Modules\Article\Repository;

use App\Enum\GeneralParamsEnum;
use App\Helpers\Eloquent\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Article\Filter\ArticleSearchFilter;
use Modules\Article\Models\Article;

class ArticleRepository extends BaseRepository
{
    public function model(): string
    {
        return Article::class;
    }

    public function searchWithPagination(ArticleSearchFilter $filter): LengthAwarePaginator
    {
        $query = $this->getFilterQuery($filter);

        return $query->paginate(GeneralParamsEnum::PAGINATION_LIMIT->value);
    }

    /**
     * @param ArticleSearchFilter $filter
     * @param int $limit
     * @return Collection<int,Article>
     */
    public function searchWithLimit(ArticleSearchFilter $filter, int $limit): Collection
    {
        /** @var Builder<Article> $query */
        $query = $this->getFilterQuery($filter);

        return $query
            ->with(['author', 'category', 'source'])
            ->limit($limit)
            ->get();
    }

    public function show(int $id): Model
    {
        return Article::query()->findOrFail($id)->first();
    }

    /**
     * @param ArticleSearchFilter $filter
     * @return Builder<Article>
     */
    private function getFilterQuery(ArticleSearchFilter $filter): Builder
    {
        return Article::query()
            ->when(!empty($filter->title), function ($query) use ($filter) {
                $query->where('title', 'like', '%' . $filter->title . '%');
            })
            ->when(!empty($filter->source_id), function ($query) use ($filter) {
                $query->where('source_id', $filter->source_id);
            })
            ->when(!empty($filter->category_id), function ($query) use ($filter) {
                $query->where('category_id', $filter->category_id);
            })
            ->when(!empty($filter->author_id), function ($query) use ($filter) {
                $query->where('author_id', $filter->author_id);
            })
            ->when(!empty($filter->keywords), function ($query) use ($filter) {
                $query->where(function ($q) use ($filter) {
                    foreach ($filter->keywords as $keyword) {
                        $q->orWhere('key_words', 'like', '%' . $keyword . '%');
                    }
                });
            })
            ->when(!empty($filter->start_date) && !empty($filter->end_date), function ($query) use ($filter) {
                $query->whereBetween('created_at', [$filter->date, $filter->date]);
            });
    }
}
