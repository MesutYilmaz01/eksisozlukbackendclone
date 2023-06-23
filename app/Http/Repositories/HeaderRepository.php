<?php

namespace App\Http\Repositories;

use App\Http\RepositoryContracts\IHeaderRepository;
use App\Models\Header;
use Illuminate\Database\Eloquent\Collection;

class HeaderRepository extends BaseRepository implements IHeaderRepository
{
    public function getByHeader(string $header): ?Header
    {
        return $this->findByAttributes([
            ['header', '=', $header]
        ]);
    }

    public function store(array $data): Header
    {
        return $this->create($data);
    }

    public function getAll(array $with): Collection
    {
        return $this->allWith($with);
    }

    public function show(string $header): ?Header
    {
        return $this->findByAttributesWith([
            ['slug', '=', $header]
        ],
        ['entries', 'entries.user']);
    }
}

