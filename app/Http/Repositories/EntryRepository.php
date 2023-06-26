<?php

namespace App\Http\Repositories;

use App\Http\RepositoryContracts\IEntryRepository;
use App\Models\Entry;

class EntryRepository extends BaseEloquentRepository implements IEntryRepository
{
    protected $model = Entry::class;
}

