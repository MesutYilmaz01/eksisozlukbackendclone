<?php

namespace App\Http\RepositoryContracts;

use App\Models\Header;

interface IHeaderRepository
{
    public function getByHeader(string $header): ?Header;

    public function store(array $data): Header;
}