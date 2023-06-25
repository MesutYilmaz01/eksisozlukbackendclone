<?php

namespace App\Http\RepositoryContracts;

use App\Models\User;

interface IUserRepository
{
    public function updateById(int $id, array $data);

    public function deleteById(int $id): bool;
}