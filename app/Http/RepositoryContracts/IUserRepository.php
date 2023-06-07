<?php

namespace App\Http\RepositoryContracts;

use App\Models\User;

interface IUserRepository
{
    public function store(array $data): User;

    public function update(array $data);
}