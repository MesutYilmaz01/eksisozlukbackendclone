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

    public function deleteById(int $id): bool
    {
        return User::query()->where(['id' => $id])->delete();
    }

    public function getById(int $id): ?User
    {
        return User::query()->where(['id' => $id])->first();
    }

    public function getByUsername(string $username): ?User
    {
        return User::query()->where(['username' => $username])->first();
    }
}