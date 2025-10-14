<?php

namespace App\Repositories\Eloquent;

use App\Repositories\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BaseRepository implements EloquentRepositoryInterface
{
    /**
     * @var Model
     */
    protected Model $model;

    /**
     * BaseRepository constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @inheritdoc
     */
    public function paginate(int $perPage = 15, array $columns = ['*'], array $relations = []): LengthAwarePaginator
    {
        return $this->model->with($relations)->paginate($perPage, $columns);
    }

    /**
     * @inheritdoc
     */
    public function all(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->with($relations)->get($columns);
    }

    /**
     * @inheritdoc
     */
    public function find(int $modelId, array $columns = ['*'], array $relations = []): ?Model
    {
        return $this->model->select($columns)->with($relations)->find($modelId);
    }

    /**
     * @inheritdoc
     */
    public function create(array $payload): Model
    {
        return $this->model->create($payload);
    }

    /**
     * @inheritdoc
     */
    public function update(int $modelId, array $payload): bool
    {
        $model = $this->find($modelId);

        return $model->update($payload);
    }

    /**
     * @inheritdoc
     */
    public function delete(int $modelId): bool
    {
        return $this->find($modelId)->delete();
    }

    /**
     * @inheritdoc
     */
    public function findMany(array $modelIds, array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->select($columns)->with($relations)->whereIn('id', $modelIds)->get();
    }

    /**
     * @inheritdoc
     */
    public function deleteMany(array $modelIds): bool
    {
        return $this->model->whereIn('id', $modelIds)->delete();
    }
}
