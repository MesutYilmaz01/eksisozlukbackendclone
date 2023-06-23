<?php

namespace App\Http\RepositoryContracts;

use App\Models\Header;
use Illuminate\Database\Eloquent\Collection;

interface IHeaderRepository
{
    public function getByHeader(string $header): ?Header;

    public function store(array $data): Header;

    public function getAll(array $with): Collection;
}