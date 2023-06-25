<?php

namespace App\Http\RepositoryContracts;

use App\Models\Chat;

interface IChatRepository
{
    public function getFirstOrCreate(array $data): Chat;
}