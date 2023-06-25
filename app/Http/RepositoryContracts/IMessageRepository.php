<?php

namespace App\Http\RepositoryContracts;

use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;

interface IMessageRepository
{
    public function get(int $userOne, int $userTwo): ?Collection;

    public function updateByIds(array $ids, int $authenticatedUserId);

    public function updateByChatId(int $chatId, int $authenticatedUserId);

    public function getByChatIdAndUserId(int $chatId, int $authenticatedUserId);

}