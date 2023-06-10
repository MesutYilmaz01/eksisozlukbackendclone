<?php

namespace App\Http\RepositoryContracts;

use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;

interface IMessageRepository
{
    public function store(array $data): Message;

    public function get(int $userOne, int $userTwo): ?Collection;
}