<?php

namespace Modules\Article\Service;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Article\Models\Source;
use Modules\Article\Repository\SourceRepository;

readonly class SourceService
{
    public function __construct(
        private SourceRepository $sourceRepository
    ) {}

    /**
     * @return LengthAwarePaginator<Source>
     */
    public function getList(): LengthAwarePaginator
    {
        return $this->sourceRepository->list();
    }

    public function getDetails(int $id): Source
    {
        return $this->sourceRepository->getById($id);
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
        $this->sourceRepository->create($dataToSave);
    }

    /**
     * @param array{
     *     name:string|null,
     *     status:int | null
     * } $data
     */
    public function update(array $data, int $id): void
    {
        $record = Source::query()->findOrFail($id);

        $dataToUpdate = array_filter($data, fn ($item) => ! is_null($item));
        $record->update($dataToUpdate);
    }

    public function delete(int $id): void
    {
        $record = Source::query()->findOrFail($id);
        $record->delete();
    }
}
