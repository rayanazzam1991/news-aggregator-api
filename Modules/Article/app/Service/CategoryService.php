<?php

namespace Modules\Article\Service;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Article\Models\Category;
use Modules\Article\Repository\CategoryRepository;

readonly class CategoryService
{
    public function __construct(
        private CategoryRepository $categoryRepository
    ) {}

    /**
     * @return LengthAwarePaginator<Category>
     */
    public function getList(): LengthAwarePaginator
    {
        return $this->categoryRepository->list();
    }

    public function getDetails(int $id): Category
    {
        return $this->categoryRepository->getById($id);
    }

    /**
     * @param array{
     *     name:string,
     *     status:int | null
     * } $data
     */
    public function create(array $data): void
    {
        $dataToSave = array_filter($data, fn ($item) => ! is_null($item));
        $this->categoryRepository->create($dataToSave);
    }

    /**
     * @param array{
     *     name:string|null,
     *     status:int | null
     * } $data
     */
    public function update(array $data, int $id): void
    {
        $record = Category::query()->findOrFail($id);

        $dataToUpdate = array_filter($data, fn ($item) => ! is_null($item));
        $record->update($dataToUpdate);
    }

    public function delete(int $id): void
    {
        $record = Category::query()->findOrFail($id);
        $record->delete();
    }
}
