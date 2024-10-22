<?php

namespace Modules\Article\Repository;

use App\Enum\GeneralParamsEnum;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TModel of Model
 */
abstract class BaseRepository
{
    /**
     * The repository model instance.
     *
     * @var TModel
     */
    protected Model $model;

    /**
     * BaseRepository constructor.
     *
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->makeModel();
    }

    /**
     * Create an instance of the model.
     *
     * @return TModel
     *
     * @throws BindingResolutionException
     * @throws Exception
     */
    public function makeModel(): Model
    {
        $model = app()->make($this->model());

        if (! $model instanceof Model) {
            throw new Exception("Class {$this->model()} must be an instance of ".Model::class);
        }

        return $this->model = $model;
    }

    /**
     * Define the model class name for the repository.
     *
     * @return class-string<TModel>
     */
    abstract public function model(): string;

    /**
     * Get a paginated list of the model.
     *
     * @return LengthAwarePaginator<TModel>
     */
    public function list(): LengthAwarePaginator
    {
        return $this->model::query()->paginate(GeneralParamsEnum::PAGINATION_LIMIT->value);
    }

    /**
     * Get a paginated list of the model.
     */
    public function getAll(): Collection
    {
        return $this->model::query()->get();
    }

    /**
     * Get a model by its ID.
     *
     * @return TModel
     */
    public function getById(int $id): Model
    {
        return $this->model::query()->findOrFail($id);
    }

    /**
     * Create a new record in the model.
     */
    public function create(array $data): void
    {
        $this->model::query()->create($data);
    }

    /**
     * Update an existing record in the model by its ID.
     */
    public function update(array $data, int $id): void
    {
        $record = $this->model::query()->findOrFail($id);
        $record->update($data);
    }

    /**
     * Soft delete a record by its ID.
     */
    public function delete(int $id): void
    {
        $record = $this->model::query()->findOrFail($id);
        $record->delete();
    }

    public function exists(int $id): bool
    {
        return $this->model::query()->find($id) !== null;
    }
}
