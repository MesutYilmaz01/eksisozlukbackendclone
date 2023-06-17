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

    public function deleteById(int $id)
    {
        return Entry::where('id', $id)->delete();
    }

    public function updateById(int $id, array $data)
    {
        return Entry::where('id', $id)->update($data);
    }

    public function getById(int $id): ?Entry 
    {
        return Entry::where('id', $id)->first();
    }
}

