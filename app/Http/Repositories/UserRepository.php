<?php

namespace App\Http\Repositories;

use App\Http\RepositoryContracts\IUserRepository;
use App\Models\User;

class UserRepository extends BaseEloquentRepository implements IUserRepository
{
    protected $model = User::class;
    
    public function updateById(int $id, array $data)
    {
        return $this->model->where(['id' => $id])->update($data);
    }

    public function deleteById(int $id): bool
    {
        return $this->model->query()->where(['id' => $id])->delete();
    }
}