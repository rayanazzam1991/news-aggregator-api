<?php

namespace Modules\Article\Service;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Article\Models\Author;
use Modules\Article\Repository\AuthorRepository;

readonly class AuthorService
{
    public function __construct(
        private AuthorRepository $authorRepository
    ) {}

    /**
     * @return LengthAwarePaginator<Author>
     */
    public function getList(): LengthAwarePaginator
    {
        return $this->authorRepository->list();
    }

    public function getDetails(int $id): Author
    {
        return $this->authorRepository->getById($id);
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
        $this->authorRepository->create($dataToSave);
    }

    /**
     * @param array{
     *     name:string|null,
     *     status:int | null
     * } $data
     */
    public function update(array $data, int $id): void
    {
        $record = Author::query()->findOrFail($id);

        $dataToUpdate = array_filter($data, fn ($item) => ! is_null($item));
        $record->update($dataToUpdate);
    }

    public function delete(int $id): void
    {
        $record = Author::query()->findOrFail($id);
        $record->delete();
    }
}
