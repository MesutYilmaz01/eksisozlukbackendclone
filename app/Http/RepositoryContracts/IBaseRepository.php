<?php

namespace App\Http\RepositoryContracts;

use Closure;
use Illuminate\Database\Eloquent\Model;

interface IBaseRepository
{
    /**
     * Returns model by id
     * @param $entityId
     * @return  $model
     * @internal param array|int $id
     */
    public function find($entityId);

    /**
     * Return collection by ids
     * @param array $entityIds
     * @return $collection
     */
    public function findMany(array $entityIds);

    /**
     * Return collection by ids with trashed
     * @param array $entityIds
     * @return $collection
     */
    public function findManyIncludingTrashed(array $entityIds);

    /**
     * Returns model by id with given relations
     * @param int $entityId
     * @param array $with
     * @return  $model
     * @internal param array|int $id
     */
    public function findWith(int $entityId, array $with);
    
    /**
     * Return collection by ids with given relationship
     * @param array $entityIds
     * @param array $with
     * @return mixed
     */
    public function findManyWith(array $entityIds, array $with);

    /**
     * Return collection by ids with given relationship with trashed
     * @param array $entityIds
     * @param array $with
     * @param array $columns
     * @return mixed
     */
    public function findManyWithIncludingTrashed(array $entityIds, array $with, array $columns);

    /**
     * Returns model by uuid
     * @param string $entityUuid
     * @return Model
     */
    public function findByUuid(string $entityUuid): ?Model;

    /**
     * Return collection by uuid with given relation
     * @param string $entityUuid
     * @param array $with
     * @return Model
     */
    public function findByUuidWith(string $entityUuid, array $with): ?Model;

    /**
     * Return model by id with trashed
     * @param int $entityId
     * @return mixed
     */
    public function findIncludingTrashed(int $entityId);

    /**
     * Return model by id with given relation with trashed
     * @param int $entityId
     * @param array $with
     * @return mixed
     */
    public function findWithIncludingTrashed(int $entityId, array $with);

    /**
     * Return model if exists if not, create new
     * @param  $attributes
     * @return static
     */
    public function firstOrCreate($attributes);

    /**
     * Returns all records.
     * @return Collection
     */
    public function all();

    /**
     * Return all records with given relationship
     * @param array $with
     * @return Collection
     */
    public function allWith(array $with);

    /**
     * Return with paginated
     * @param array $params
     * @return mixed
     */
    public function paginate($params = []);

    /**
     * Creates new record and return it
     * @param array $data
     * @return mixed
     */
    public function create($data);

    /**
     * Create or update model
     *
     * @param  $attributes
     * @param  $values
     * @return mixed
     */
    public function updateOrCreate($attributes, $values = []);

    /**
     * Updates model
     * @param  $model
     * @param array $data
     * @return mixed
     */
    public function update($model, $data);

    /**
     * Deletes model
     * @param $model
     * @return mixed
     */
    public function destroy($model);

    /**
     * Recover model
     * @param $model
     * @return mixed
     */
    public function restore($model);

    /**
     * Force deletes model
     * @param $model
     * @return mixed
     */
    public function purge($model);

    /**
     * Returns model by given attributes
     * @param array $attributes
     * @return object
     */
    public function findByAttributes(array $attributes);

    /**
     * Returns collection by given attributes
     * @param array $attributes
     * @return object
     */
    public function findManyByAttributes(array $attributes);

    /**
     * @param array $attributes
     * @return mixed
     */
    public function findManyLikeByAttributes(array $attributes);

    /**
     * Returns model by given attributes with given relationship
     * @param array $attributes
     * @param array $with
     * @return mixed
     */
    public function findByAttributesWith(array $attributes, array $with);

    /**
     * Returns collection by given attributes with given relationship
     * @param array $attributes
     * @param array $with
     * @return mixed
     */
    public function findManyByAttributesWith(array $attributes, array $with);

    /**
     * Returns collection by given attributes with given relationship with trashed
     * @param array $attributes
     * @param array $with
     * @return Model|null
     */
    public function findByAttributesWithIncludingTrashed(array $attributes, array $with): ?Model;


    /**
     * Returns model
     * @return mixed
     */
    public function getModel();

    /**
     * @param $model
     * @return $this
     */
    public function setModel($model);

    /**
     * Begins a transaction
     */
    public function transaction(Closure $closure);

    /**
     * @param array $params
     * @return mixed
     */
    public function search($params = []);
}