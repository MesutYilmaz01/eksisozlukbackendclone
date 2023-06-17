<?php

namespace App\Http\RepositoryContracts;

use App\Models\Entry;

interface IEntryRepository
{
    public function store(array $data): Entry;
}