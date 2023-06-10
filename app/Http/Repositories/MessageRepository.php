<?php

namespace App\Http\Repositories;

use App\Http\RepositoryContracts\IMessageRepository;
use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;

class MessageRepository implements IMessageRepository
{
    public function store(array $data): Message
    {
        return Message::create($data);
    }

    public function get(int $userOne, int $userTwo): ?Collection
    {
        return Message::query()
                ->where(['sender_id' => $userOne, 'receiver_id' => $userTwo])
                ->orWhere(['sender_id' => $userTwo, 'receiver_id' => $userOne])
                ->get();
    }

}