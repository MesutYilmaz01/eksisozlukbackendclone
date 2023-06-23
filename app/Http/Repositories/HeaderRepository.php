<?php

namespace App\Http\Repositories;

use App\Http\RepositoryContracts\IHeaderRepository;
use App\Models\Header;
use Illuminate\Database\Eloquent\Collection;

class HeaderRepository implements IHeaderRepository
{
    public function getByHeader(string $header): ?Header
    {
        return Header::query()->where('header', strtolower($header))->first();
    }

    public function store(array $data): Header
    {
        return Header::query()->create($data);
    }

    public function getAll(array $with): Collection
    {
        return Header::query()->with($with['with'] ?? [])->get();
    }
}

