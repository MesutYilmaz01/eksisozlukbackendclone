<?php

namespace App\Http\Repositories;

use App\Http\RepositoryContracts\IBaseEloquentRepository;
use App\Repositories\BaseRepository\Traits\CacheResults;
use App\Repositories\BaseRepository\Traits\ThrowsHttpExceptions;
use Closure;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Throwable;

class BaseEloquentRepository implements IBaseEloquentRepository
{
    /**
     * Name of model associated with this repository
     * @var Model|Builder
     */
    protected $model;

    protected $originalModel;

    /**
     * Array of method names of relationships available to use
     * @var array
     */
    protected array $relationships = [];

    /**
     * Array of relationships to include in next query
     * @var array
     */
    protected array $requiredRelationships = [];

    /**
     * Array of traits being used by the repository.
     * @var array
     */
    protected array $uses = [];

    protected int $cacheTtl = 60;

    protected bool $caching = true;

    private string $orderBy;

    private string $orderType;

    private bool $withPagination;

    private int $perPage;


    /**
     * Get the model from the IoC container
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->originalModel = $this->model;
        $this->renewModel();
        $this->setUses();
        $this->setOrderDefaults();
        $this->setPaginationDefaults();
    }

    /**
     * Get all items
     *
     * @param string $columns specific columns to select
     * @param string $orderBy column to sort by
     * @param string $sort sort direction
     * @return Collection|LengthAwarePaginator
     */
    public function getAll($columns = ['*'], $orderBy = null, $sort = null)
    {
        if ($this->withPagination) {
            return $this->getPaginated($this->perPage);
        }

        if ($orderBy == null) {
            $orderBy = $this->orderBy;
        }

        if ($sort == null) {
            $sort = $this->orderType;
        }

        $query = function () use ($columns, $orderBy, $sort) {

            return $this->model
                ->with($this->requiredRelationships)
                ->orderBy($orderBy, $sort)
                ->get($columns);
        };

        return $this->doQuery($query);
    }

    public function getQuery(): Builder
    {
        return $this->model
            ->with($this->requiredRelationships);
    }


    /**
     * Get all items
     *
     * @param string[] $columns specific columns to select
     * @param null $secondOrderBy column to sort by
     * @param null $orderBy
     * @param null $sort sort direction
     * @return Collection|LengthAwarePaginator
     */
    public function getAllWithDoubleOrderBy($columns = ['*'], $secondOrderBy = null, $orderBy = null, $sort = null)
    {
        if ($this->withPagination) {
            return $this->getPaginatedWithDoubleOrderBy($this->perPage, $secondOrderBy, $orderBy, $sort);
        }

        if ($secondOrderBy == null) {
            $secondOrderBy = $this->orderBy;
        }

        if ($orderBy == null) {
            $orderBy = $this->orderBy;
        }

        if ($sort == null) {
            $sort = $this->orderType;
        }

        $query = function () use ($secondOrderBy, $columns, $orderBy, $sort) {

            return $this->model
                ->with($this->requiredRelationships)
                ->orderBy($secondOrderBy, $sort)
                ->orderBy($orderBy, $sort)
                ->get($columns);
        };

        return $this->doQuery($query);
    }

    public function count()
    {
        $query = function () {

            return $this->model
                ->count();
        };

        return $this->doQuery($query);
    }

    /**
     * Get paged items
     *
     * @param integer $paged Items per page
     * @param string $orderBy Column to sort by
     * @param string $sort Sort direction
     * @return Paginator
     */
    public function getPaginated($paged = 15, $orderBy = null, $sort = null): LengthAwarePaginator
    {
        if ($orderBy == null) {
            $orderBy = $this->orderBy;
        }

        if ($sort == null) {
            $sort = $this->orderType;
        }

        $query = function () use ($paged, $orderBy, $sort) {

            return $this->model
                ->with($this->requiredRelationships)
                ->orderBy($orderBy, $sort)
                ->paginate($paged);
        };

        return $this->doQuery($query);
    }

    /**
     * Get paged items
     *
     * @param integer $paged Items per page
     * @param string $secondOrderBy Column to sort by
     * @param string $orderBy Column to sort by
     * @param string $sort Sort direction
     * @return LengthAwarePaginator
     */
    public function getPaginatedWithDoubleOrderBy(
        $paged = 15,
        $secondOrderBy = null,
        $orderBy = null,
        $sort = null
    ): LengthAwarePaginator {
        if ($orderBy == null) {
            $orderBy = $this->orderBy;
        }

        if ($secondOrderBy == null) {
            $secondOrderBy = $this->orderBy;
        }

        if ($sort == null) {
            $sort = $this->orderType;
        }

        $query = function () use ($secondOrderBy, $paged, $orderBy, $sort) {

            return $this->model
                ->with($this->requiredRelationships)
                ->orderBy($secondOrderBy, $sort)
                ->orderBy($orderBy, $sort)
                ->paginate($paged);
        };

        return $this->doQuery($query);
    }

    /**
     * Items for select options
     *
     * @param string $data column to display in the option
     * @param string $key column to be used as the value in option
     * @param string $orderBy column to sort by
     * @param string $sort sort direction
     * @return array           array with key value pairs
     */
    public function getForSelect(string $data, $key = 'id', $orderBy = null, $sort = null): array
    {
        if ($orderBy == null) {
            $orderBy = $this->orderBy;
        }

        if ($sort == null) {
            $sort = $this->orderType;
        }

        $query = function () use ($data, $key, $orderBy, $sort) {
            return $this->model
                ->with($this->requiredRelationships)
                ->orderBy($orderBy, $sort)
                ->pluck($data, $key)
                ->all();
        };

        return $this->doQuery($query);
    }

    /**
     * Get item by its id
     *
     * @param int $modelId
     * @return Model
     */
    public function getById(int $modelId): ?Model
    {
        $query = function () use ($modelId) {
            return $this->model
                ->with($this->requiredRelationships)
                ->find($modelId);
        };

        return $this->doQuery($query);
    }

    public function getManyById(array $modelIds)
    {
        $query = function () use ($modelIds) {
            return $this->model
                ->with($this->requiredRelationships)
                ->findMany($modelIds);
        };

        return $this->doQuery($query);
    }

    public function getByUuid(string $modelUuid): ?Model
    {
        $query = function () use ($modelUuid) {
            return $this->model
                ->where('uuid', $modelUuid)
                ->first();
        };
        return $this->doQuery($query);
    }

    /**
     * @param $uuid
     * @param array $with
     * @return Builder|Model|object|null
     */
    public function findByUuidWith($uuid, array $with): ?Model
    {
        return $this->model->with($with)->where('uuid', $uuid)->first();
    }

    /**
     * Get instance of model by column
     *
     * @param mixed $term search term
     * @param string $column column to search
     * @return Model
     */
    public function getItemByColumn($term, $column = 'slug'): Model
    {
        $query = function () use ($term, $column) {
            return $this->model
                ->with($this->requiredRelationships)
                ->where($column, '=', $term)
                ->first();
        };

        return $this->doQuery($query);
    }

    /**
     * Get instance of model by column
     *
     * @param mixed $term search term
     * @param string $column column to search
     * @return Collection
     */
    public function getCollectionByColumn($term, $column = 'slug'): Collection
    {
        $query = function () use ($term, $column) {
            return $this->model
                ->with($this->requiredRelationships)
                ->where($column, '=', $term)
                ->get();
        };

        return $this->doQuery($query);
    }

    /**
     * Get item by id or column
     *
     * @param mixed $term id or term
     * @param string $column column to search
     * @return Model
     */
    public function getActively($term, $column = 'slug'): Model
    {
        if (is_numeric($term)) {
            return $this->getById($term);
        }

        return $this->getItemByColumn($term, $column);
    }

    /**
     * Create new using mass assignment
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data): ?Model
    {
        return $this->model->create($data);
    }

    /**
     * This methods add new item for the has many relation
     *
     * @param HasMany|HasOne $relation
     * @param array $data
     * @return Model|null
     */
    public function createChild($relation, array $data): ?Model
    {
        return $relation->create($data);
    }

    /**
     * This methods add new items for the has many relation
     *
     * @param HasMany $relation
     * @param array $data
     * @return Collection
     */
    public function createChildren(HasMany $relation, array $data): Collection
    {
        return $relation->createMany($data);
    }

    public function update(Model $model, array $data): ?Model
    {
        $model->fill($data)->save();
        return $model;
    }

    /**
     * Update a record using the primary key.
     *
     * @param $modelId
     * @param $data array
     * @return bool
     */
    public function updateMass($modelId, array $data): bool
    {
        return $this->model->where($this->model->getKeyName(), $modelId)->update($data);
    }

    /**
     * Update or crate a record and return the entity
     *
     * @param array $attributes
     * @param array $values
     * @return mixed
     */
    public function updateOrCreate(array $attributes, array $values = []): ?Model
    {
        return $this->model->updateOrCreate($attributes, $values);
    }

    /**
     * Update or crate a record and return the entity
     *
     * @param HasMany|HasOne $relation
     * @param array $attributes
     * @param array $values
     * @return mixed
     */
    public function updateOrCreateForChild($relation, array $attributes, array $values = []): ?Model
    {
        return $relation->updateOrCreate($attributes, $values);
    }

    /**
     * @param array $attributes
     * @param array $values
     * @return Builder|Model|mixed
     */
    public function firstOrCreate(array $attributes, array $values = [])
    {
        return $this->model->firstOrCreate($attributes, $values);
    }

    /**
     * Delete a record by the primary key.
     *
     * @param $modelId
     * @return bool
     * @throws Exception
     */
    public function deleteMass($modelId): bool
    {
        return $this->model->where($this->model->getKeyName(), $modelId)->delete();
    }

    /**
     * Delete a record by its model
     * @param Model $model
     * @return bool|null
     * @throws Exception
     */
    public function delete(Model $model): ?bool
    {
        return $model->delete();
    }

    /**
     * Restore a record by its model
     * @param Model $model
     * @return bool|null
     * @throws Exception
     */
    public function restore(Model $model): ?bool
    {
        return $model->restore();
    }

    /**
     * @param Model $model
     * @return bool|null
     */
    public function forceDelete(Model $model): ?bool
    {
        return $model->forceDelete();
    }

    /**
     * Choose what relationships to return with query.
     *
     * @param mixed $relationships
     * @return $this
     */
    public function with($relationships): BaseEloquentRepository
    {
        $this->requiredRelationships = [];

        if ($relationships == 'all') {
            $this->requiredRelationships = $this->relationships;
        } elseif (is_array($relationships)) {
            $this->requiredRelationships = array_filter($relationships, function ($value) {
                return in_array($value, $this->relationships);
            });
        } elseif (is_string($relationships)) {
            array_push($this->requiredRelationships, $relationships);
        }

        return $this;
    }

    /**
     * Determines to get also soft deleted rows
     * @return $this
     */
    public function withTrashed(): BaseEloquentRepository
    {
        $this->model = $this->model->withTrashed();
        return $this;
    }

    public function parseRequest(array $requestArray): void
    {
        $this->setOrder($requestArray);

        $this->setTrashed($requestArray);

        $this->setPagination($requestArray);
    }

    /**
     * Determines only to get soft deleted rows
     * @return $this
     */
    public function onlyTrashed(): BaseEloquentRepository
    {
        $this->model = $this->model->onlyTrashed();
        return $this;
    }

    public function withFilters($filter): BaseEloquentRepository
    {
        $this->model = $this->model->filter($filter);
        return $this;
    }

    /**
     * Perform the repository query.
     *
     * @param $callback
     * @return mixed
     */
    protected function doQuery($callback)
    {
        $previousMethod = debug_backtrace(null, 2)[1];
        $methodName = $previousMethod['function'];
        $arguments = $previousMethod['args'];

        $result = $this->doBeforeQuery($callback, $methodName, $arguments);

        return $this->doAfterQuery($result, $methodName, $arguments);
    }

    /**
     *  Apply any modifiers to the query.
     *
     * @param $callback
     * @param $methodName
     * @param $arguments
     * @return mixed
     */
    private function doBeforeQuery($callback, $methodName, $arguments)
    {
        $traits = $this->getUsedTraits();
        
        if (in_array(CacheResults::class, $traits) && $this->caching && $this->isCacheableMethod($methodName)) {
            return $this->processCacheRequest($callback, $methodName, $arguments);
        }

        return $callback();
    }

    /**
     * Handle the query result.
     *
     * @param $result
     * @param $methodName
     * @param $arguments
     * @return mixed
     */
    private function doAfterQuery($result, $methodName, $arguments)
    {
        $traits = $this->getUsedTraits();

        if (in_array(CacheResults::class, $traits)) {
            // Reset caching to enabled in case it has just been disabled.
            $this->caching = true;
        }

        if (in_array(ThrowsHttpExceptions::class, $traits)) {
            if ($this->shouldThrowHttpException($result, $methodName)) {
                $this->throwNotFoundHttpException($methodName, $arguments);
            }

            $this->exceptionsDisabled = false;
        }

        $this->renewModel();

        return $result;
    }

    /**
     * @return int
     */
    protected function getCacheTtl(): int
    {
        return $this->cacheTtl;
    }

    /**
     * @return $this
     */
    protected function setUses(): BaseEloquentRepository
    {
        $this->uses = array_flip(class_uses_recursive(get_class($this)));

        return $this;
    }

    /**
     * @return array
     */
    protected function getUsedTraits(): array
    {
        return $this->uses;
    }

    private function setOrderDefaults(): void
    {
        $this->orderBy = "id";
        $this->orderType = "desc";
    }

    private function setPaginationDefaults(): void
    {
        $this->withPagination = false;
        $this->perPage = 30;
    }

    private function setOrder($requestArray): void
    {
        $this->setOrderDefaults();

        if (isset($requestArray["order_by"])) {
            $this->orderBy = $requestArray["order_by"];
        }

        if (isset($requestArray["order_type"])) {
            $this->orderType = $requestArray["order_type"];
        }
    }

    /**
     * Determines by the 'with_trashed' item of request array
     * @param $requestedArray
     */
    private function setTrashed($requestedArray): void
    {
        if (isset($requestedArray["with_trashed"]) && convertToBoolean($requestedArray["with_trashed"])) {
            $this->model = $this->model->withTrashed();
            return;
        }

        if (isset($requestedArray["only_trashed"])) {
            $this->model = $this->model->onlyTrashed();
        }
    }

    private function setPagination(array $requestArray)
    {
        $this->setPaginationDefaults();

        if (isset($requestArray["with_pagination"]) && convertToBoolean($requestArray["with_pagination"])) {
            $this->withPagination = true;
        }

        if (isset($requestArray["per_page"]) && is_numeric($requestArray["per_page"])) {
            $this->perPage = (int)$requestArray["per_page"];
        }
    }

    private function renewModel()
    {
        $this->model = app()->make($this->originalModel);
    }

    /**
     * @throws Throwable
     */
    public function beginTransaction()
    {
        DB::beginTransaction();
    }

    /**
     * @throws Throwable
     */
    public function rollback()
    {
        DB::rollBack();
    }

    /**
     * @throws Throwable
     */
    public function commit()
    {
        DB::commit();
    }

    /**
     * @param Closure $closure
     * @return mixed
     * @throws Throwable
     */
    public function transaction(Closure $closure)
    {
        return DB::transaction($closure);
    }

    public function findByAttributes(array $attributes): ?Model
    {
        return $this->model->where($attributes)->first();
    }
}

