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
     * Get item by its id
     *
     * @param int $modelId
     */
    public function getById(int $modelId);

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
     * @param array $attributes
     * @param array $values
     * @return mixed
     */
    public function firstOrCreate(array $attributes, array $values = []);

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