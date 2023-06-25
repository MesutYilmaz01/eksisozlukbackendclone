<?php

namespace App\Http\RepositoryContracts;

use App\Models\Entry;

interface IEntryRepository
{
    public function deleteById(int $id);

    public function updateById(int $id, array $data);
}