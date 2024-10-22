<?php

namespace Modules\UserPreferences\Service;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Article\Contracts\NewsFeedPublicInterface;
use Modules\UserPreferences\Repository\UserPreferenceRepository;

readonly class UserPreferenceFeedService
{
    public function __construct(
        private UserPreferenceRepository $preferenceRepository,
        private NewsFeedPublicInterface $newsFeedPublicService
    ) {}

    public function fetch(int $userId): LengthAwarePaginator
    {
        $userPreferences = $this->preferenceRepository->searchAll($userId, 10);
        $request = [];
        $perPage = 10;

        foreach ($userPreferences as $preference) {
            $request[] = [
                'preference_id' => $preference->preference_id,
                'preference_type' => $preference->preference_type, // Use preference_type instead of preference_id here
            ];
        }

        // Get the articles based on the preferences and ensure it's a collection
        $articles = $this->newsFeedPublicService->getArticles($request);

        // Paginate the articles collection (assuming articles is a collection)
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $articlesPaginated = $this->paginateCollection($articles, $perPage, $currentPage);

        return $articlesPaginated;

    }

    public function paginateCollection(Collection $collection, int $perPage, ?int $page = null): LengthAwarePaginator
    {
        $page = $page ?: (LengthAwarePaginator::resolveCurrentPage() ?: 1);
        $total = $collection->count();
        $items = $collection->slice(($page - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator($items, $total, $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'query' => request()->query(),
        ]);
    }
}
