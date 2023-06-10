<?php

namespace App\Http\RepositoryContracts;

use App\Models\User;

interface IUserRepository
{
    public function store(array $data): User;

    public function updateById(int $id, array $data);

    public function deleteById(int $id): bool;

    public function getById(int $id): ?User;

    public function getByUsername(string $username): ?User;

}