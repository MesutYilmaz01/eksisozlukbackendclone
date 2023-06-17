<?php

namespace App\Http\Repositories;

use App\Http\RepositoryContracts\IEntryRepository;
use App\Models\Entry;

class EntryRepository implements IEntryRepository
{
    public function store(array $data): Entry
    {
        return Entry::create($data);
    }
}

