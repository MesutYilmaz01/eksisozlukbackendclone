<?php

namespace App\Http\Repositories;

use App\Http\RepositoryContracts\IBaseRepository;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class BaseRepository implements IBaseRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var Builder
     */
    protected $query;


    public function __construct(Model $model = null)
    {
        if ($model) {
            $this->model = $model;
        }
    }

    /**
     * Returns model by id
     * @param $entityId
     * @return Model
     */
    public function find($entityId)
    {
        return $this->model->find($entityId);
    }

    /**
     * @param array $entityIds
     * @return Collection
     */
    public function findMany(array $entityIds)
    {
        return $this->model->findMany($entityIds);
    }

    /**
     * @param array $entityIds
     * @return Collection
     */
    public function findManyIncludingTrashed(array $entityIds)
    {
        return $this->model->withTrashed()->findMany($entityIds);
    }

    /**
     * @param int $entityId
     * @param array $with
     * @return Builder|Builder[]|Collection|Model|null
     */
    public function findWith(int $entityId, array $with)
    {
        return $this->model->with($with)->find($entityId);
    }

    /**
     * @param array $entityIds
     * @param array $with
     * @return Collection|null
     */
    public function findManyWith(array $entityIds, array $with)
    {
        return $this->model->with($with)->findMany($entityIds);
    }

    /**
     * @param array $entityIds
     * @param array $with
     * @param array $columns
     * @return Collection|null
     */
    public function findManyWithIncludingTrashed(array $entityIds, array $with, array $columns = ['*'])
    {
        return $this->model->withTrashed()->with($with)->findMany($entityIds, $columns);
    }

    /**
     * @param  string  $entityUuid
     * @return Model
     */
    public function findByUuid(string $entityUuid): ?Model
    {
        return $this->model->where('uuid', $entityUuid)->first();
    }

    /**
     * @param string $entityUuid
     * @param array $with
     * @return Model
     */
    public function findByUuidWith(string $entityUuid, array $with): ?Model
    {
        return $this->model->with($with)->where('uuid', $entityUuid)->first();
    }

    /**
     * @param int $entityId
     * @return Model
     */
    public function findIncludingTrashed(int $entityId)
    {
        return $this->model->withTrashed()->find($entityId);
    }

    /**
     * @param int $entityId
     * @param array $with
     * @return Model
     */
    public function findWithIncludingTrashed(int $entityId, array $with)
    {
        return $this->model->withTrashed()->with($with)->find($entityId);
    }

    /**
     * @param $attributes
     * @return BaseRepositoryContract
     */
    public function firstOrCreate($attributes)
    {
        return $this->model->firstOrCreate($attributes);
    }

    /**
     * @return Collection|Model[]|mixed
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * @param array $with
     * @return Builder[]|Collection|mixed
     */
    public function allWith(array $with)
    {
        return $this->model->with($with)->get();
    }

    /**
     * @param array $params
     * @return Collection|Model[]|mixed
     */
    public function paginate($params = [])
    {
        return $this->model->paginate($params);
    }

    /**
     * @param array $data
     * @return Model|mixed
     */
    public function create($data)
    {
        return $this->model->create($data);
    }

    /**
     * @param $attributes
     * @param array $values
     * @return Model|void
     */
    public function updateOrCreate($attributes, $values = [])
    {
        return $this->model->updateOrCreate($attributes, $values);
    }

    /**
     * @param $model
     * @param array $data
     * @return Model
     */
    public function update($model, $data)
    {
        $model->fill($data)->save();
        return $model;
    }

    /**
     * @param $model
     * @return boolean
     */
    public function destroy($model)
    {
        return $model->delete();
    }

    /**
     * @param $model
     * @return mixed
     */
    public function restore($model)
    {
        return $model->restore();
    }

    /**
     * @param $model
     * @return mixed
     */
    public function purge($model)
    {
        return $model->forceDelete();
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function findByAttributes(array $attributes)
    {
        return $this->model->where($attributes)->first();
    }

    public function findByAttributesWith(array $attributes, array $with)
    {
        return $this->model->with($with)->where($attributes)->first();
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function findManyByAttributes(array $attributes)
    {
        return $this->model->where($attributes)->get();
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function findManyLikeByAttributes(array $attributes)
    {
        return $this->model
            ->where(Arr::first(array_keys($attributes)), 'LIKE', '%' . Arr::first(array_values($attributes)) . '%')
            ->get();
    }

    /**
     * @param array $attributes
     * @param array $with
     * @return mixed
     */
    public function findManyByAttributesWith(array $attributes, array $with)
    {
        return $this->model->with($with)->where($attributes)->get();
    }

    /**
     * @param array $attributes
     * @param array $with
     * @return mixed
     */
    public function findByAttributesWithIncludingTrashed(array $attributes, array $with): ?Model
    {
        return $this->model->withTrashed()->with($with)->where($attributes)->first();
    }

    /**
     * @return Model|mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param $model
     * @return BaseRepository
     */
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
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

    /**
     * @param array $params
     * @return LengthAwarePaginator|Builder[]|Collection|mixed
     */
    public function search($params = [])
    {
        if (isset($params['with_server'])) {
            if (!isset($params['with'])) {
                $params['with'] = $params['with_server'];
            }
            $params['with'] = array_merge($params['with_server'], $params['with']);
        }
        if (isset($params["with"])) {
            $this->query->with($params["with"]);
        }

        if ($params['with_pagination'] === 'active') {
            return $this->query->paginate($params["per_page"])->appends($params);
        }

        return $this->query
            ->forPage($params["page"], $params["per_page"])
            ->get();
    }
}

