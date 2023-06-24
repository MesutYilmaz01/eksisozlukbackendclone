<?php

namespace App\Http\RepositoryContracts;

use App\Http\Repositories\BaseEloquentRepository;
use Closure;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

interface IBaseEloquentRepository
{
   /**
     * Get all items
     *
     * @param string $columns specific columns to select
     * @param string $orderBy column to sort by
     * @param string $sort sort direction
     */
    public function getAll($columns = null, $orderBy = null, $sort = null);

    public function getQuery(): Builder;

    /**
     * Get all items
     *
     * @param string $columns specific columns to select
     * @param string $secondOrderBy column to sort by
     * @param string $orderBy second column to sort by
     * @param string $sort sort direction
     */
    public function getAllWithDoubleOrderBy($columns = null, $secondOrderBy = null, $orderBy = null, $sort = null);

    public function count();

    /**
     * Get paged items
     *
     * @param integer $paged Items per page
     * @param string $orderBy Column to sort by
     * @param string $sort Sort direction
     */
    public function getPaginated($paged = 15, $orderBy = null, $sort = null);

    /**
     * Get paged items
     *
     * @param integer $paged Items per page
     * @param string $secondOrderBy Column to sort by
     * @param string $orderBy Column to sort by
     * @param string $sort Sort direction
     */
    public function getPaginatedWithDoubleOrderBy($paged = 15, $secondOrderBy = null, $orderBy = null, $sort = null);

    /**
     * Items for select options
     *
     * @param string $data column to display in the option
     * @param string $key column to be used as the value in option
     * @param string $orderBy column to sort by
     * @param string $sort sort direction
     * @return array  array with key value pairs
     */
    public function getForSelect(string $data, $key = 'id', $orderBy = null, $sort = null): array;

    /**
     * Get item by its id
     *
     * @param int $modelId
     */
    public function getById(int $modelId);

    /**
     * @param array $modelIds
     */
    public function getManyById(array $modelIds);

    /**
     * @param string $modelUuid
     */
    public function getByUuid(string $modelUuid): ?Model;

    /**
     * @param string $uuid
     * @param array $with
     * @return Model|null
     */
    public function findByUuidWith(string $uuid, array $with): ?Model;

    /**
     * Get instance of model by column
     *
     * @param mixed $term search term
     * @param string $column column to search
     */
    public function getItemByColumn($term, $column = 'slug');

    /**
     * Get instance of model by column
     *
     * @param mixed $term search term
     * @param string $column column to search
     */
    public function getCollectionByColumn($term, $column = 'slug');

    /**
     * Get item by id or column
     *
     * @param mixed $term id or term
     * @param string $column column to search
     */
    public function getActively($term, $column = 'slug');

    /**
     * Create new using mass assignment
     *
     * @param array $data
     * @return Model|null
     */
    public function create(array $data): ?Model;

    /**
     * This methods add new item for the has many relation
     *
     * @param HasMany|HasOne $relation
     * @param array $data
     * @return Model|null
     */
    public function createChild($relation, array $data): ?Model;

    /**
     * @param HasMany $relation
     * @param array $data
     * @return Collection
     */
    public function createChildren(HasMany $relation, array $data): Collection;

    /**
     * Update or crate a record and return the entity
     *
     * @param array $attributes
     * @param array $values
     * @return Model|null
     */
    public function updateOrCreate(array $attributes, array $values = []): ?Model;

    /**
     * @param HasMany|HasOne $relation
     * @param array $attributes
     * @param array $values
     * @return Model|null
     */
    public function updateOrCreateForChild($relation, array $attributes, array $values = []): ?Model;

    /**
     * @param array $attributes
     * @param array $values
     * @return mixed
     */
    public function firstOrCreate(array $attributes, array $values = []);

    /**
     * Delete a record by it's ID.
     *
     * @param $modelId
     * @return bool
     */
    public function deleteMass($modelId): bool;

    /**
     * Delete a record by its model
     * @param Model $model
     * @return bool|null
     */
    public function delete(Model $model): ?bool;

    /**
     * @param Model $model
     * @return bool|null
     */
    public function restore(Model $model): ?bool;

    /**
     * @param Model $model
     * @return bool|null
     */
    public function forceDelete(Model $model): ?bool;

    /**
     * @param Model $model
     * @param array $data
     * @return mixed
     */
    public function update(Model $model, array $data): ?Model;

    /**
     * @param $modelId
     * @param array $data
     * @return bool
     */
    public function updateMass($modelId, array $data): bool;

    /**
     * @return BaseEloquentRepository
     */
    public function withTrashed(): BaseEloquentRepository;

    /**
     * @return BaseEloquentRepository
     */
    public function onlyTrashed(): BaseEloquentRepository;

    public function withFilters($filter): BaseEloquentRepository;

    /**
     * @param $relationships
     * @return BaseEloquentRepository
     */
    public function with($relationships): BaseEloquentRepository;

    /**
     * @param array $requestArray
     */
    public function parseRequest(array $requestArray): void;

    /**
     * @throws Throwable
     */
    public function beginTransaction();

    /**
     * @throws Throwable
     */
    public function rollback();

    /**
     * @throws Throwable
     */
    public function commit();

    /**
     * @param Closure $closure
     * @throws Throwable
     */
    public function transaction(Closure $closure);

    public function findByAttributes(array $attributes): ?Model;
}