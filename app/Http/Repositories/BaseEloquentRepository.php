<?php

namespace App\Http\Repositories;

use App\Http\RepositoryContracts\IBaseEloquentRepository;
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

    private string $orderBy = 'id';

    private string $orderType = 'desc';

    private bool $withPagination = false;

    private int $perPage = 30;


    /**
     * Get the model from the IoC container
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->model = app()->make($this->model);
    }

    public function parseRequest(array $requestArray): void
    {
        $this->setOrder($requestArray);

        $this->setTrashed($requestArray);

        $this->setPagination($requestArray);
    }

    /**
     * Get all items
     *
     * @param string $columns specific columns to select
     * @param string $orderBy column to sort by
     * @param string $orderType sort direction
     * @return Collection|LengthAwarePaginator
     */
    public function getAll($columns = ['*'], $orderBy = null, $orderType = null)
    {
        if ($this->withPagination) {
            return $this->getPaginated($this->perPage);
        }

        $orderBy = $orderBy ?? $this->orderBy;
        $orderType = $orderType ?? $this->orderType;

        return $this->model
                ->with($this->requiredRelationships)
                ->orderBy($orderBy, $orderType)
                ->get($columns);
    }

    /**
     * Get paged items
     *
     * @param integer $paged Items per page
     * @param string $orderBy Column to sort by
     * @param string $sort Sort direction
     * @return Paginator
     */
    public function getPaginated($paged = 15, $orderBy = null, $orderType = null): LengthAwarePaginator
    {
        $orderBy = $orderBy ?? $this->orderBy;
        $orderType = $orderType ?? $this->orderType;

        return $this->model
                ->with($this->requiredRelationships)
                ->orderBy($orderBy, $orderType)
                ->paginate($paged);
    }

    public function count()
    {
        return $this->model->count();
    }
    
    /**
     * Get item by its id
     *
     * @param int $modelId
     * @return Model
     */
    public function getById(int $modelId): ?Model
    {
        return $this->model
                ->with($this->requiredRelationships)
                ->find($modelId);
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
     * @param array $attributes
     * @param array $values
     * @return Builder|Model|mixed
     */
    public function firstOrCreate(array $attributes, array $values = [])
    {
        return $this->model->firstOrCreate($attributes, $values);
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


    private function setOrder($requestArray): void
    {
        if (isset($requestArray["order_by"])) {
            $this->orderBy = $requestArray["order_by"];
        }

        if (isset($requestArray["order_type"])) {
            $this->orderType = $requestArray["order_type"];
        }
    }

    private function setPagination(array $requestArray)
    {
        if (isset($requestArray["with_pagination"]) && convertToBoolean($requestArray["with_pagination"])) {
            $this->withPagination = true;
        }

        if (isset($requestArray["per_page"]) && is_numeric($requestArray["per_page"])) {
            $this->perPage = (int)$requestArray["per_page"];
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

