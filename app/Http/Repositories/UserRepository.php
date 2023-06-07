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

    public function update(array $data)
    {
        return User::query()->update($data);
    }
}