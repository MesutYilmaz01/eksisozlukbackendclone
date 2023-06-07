<?php

namespace App\Http\Repositories;

use App\Http\RepositoryContracts\IUserRepository;
use App\Models\User;

class UserRepository implements IUserRepository
{
    public function store(array $data): User
    {
        return User::create($data);
    }

    public function updateById(int $id, array $data)
    {
        return User::query()->where(['id' => $id])->update($data);
    }
}