<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface EloquentRepositoryInterface
{
    /**
     * Get a paginated list of models.
     *
     * @param int $perPage
     * @param array $columns
     * @param array $relations
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $columns = ['*'], array $relations = []): LengthAwarePaginator;

    /**
     * Get all models.
     *
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function all(array $columns = ['*'], array $relations = []): Collection;

    /**
     * Find a model by its primary key.
     *
     * @param int $modelId
     * @param array $columns
     * @param array $relations
     * @return Model|null
     */
    public function find(int $modelId, array $columns = ['*'], array $relations = []): ?Model;

    /**
     * Create a new model.
     *
     * @param array $payload
     * @return Model
     */
    public function create(array $payload): Model;

    /**
     * Update a model.
     *
     * @param int $modelId
     * @param array $payload
     * @return bool
     */
    public function update(int $modelId, array $payload): bool;

    /**
     * Delete a model by its primary key.
     *
     * @param int $modelId
     * @return bool
     */
    public function delete(int $modelId): bool;

    /**
     * Find multiple models by their primary keys.
     *
     * @param array $modelIds
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function findMany(array $modelIds, array $columns = ['*'], array $relations = []): Collection;

    /**
     * Delete multiple models by their primary keys.
     *
     * @param array $modelIds
     * @return bool
     */
    public function deleteMany(array $modelIds): bool;
}
