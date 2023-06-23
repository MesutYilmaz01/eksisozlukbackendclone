<?php

namespace App\Http\RepositoryContracts;

use App\Models\Chat;

interface IChatRepository
{
    public function getFirstorCreate(array $data): Chat;

    public function getById(int $id): ?Chat;
}