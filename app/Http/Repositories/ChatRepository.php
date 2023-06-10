<?php

namespace App\Http\Repositories;

use App\Http\RepositoryContracts\IChatRepository;
use App\Models\Chat;

class ChatRepository implements IChatRepository
{
    public function firstOrCreate(array $data): Chat
    {
        $isExists = Chat::query()
            ->where(
                [
                    ['first_user_id' ,'=', $data['first_user_id']], 
                    ['second_user_id' ,'=', $data['second_user_id']]
                ])
            ->orWhere(
                [
                    ['first_user_id' ,'=', $data['second_user_id']], 
                    ['second_user_id' ,'=', $data['first_user_id']]
                ])
            ->first();

        if($isExists) {
            return $isExists;
        }

        return Chat::create(
            [
                'first_user_id' => $data['first_user_id'], 
                'second_user_id' => $data['second_user_id']
            ]);
    }

    public function getById(int $id): ?Chat
    {
        return Chat::query()->where('id', $id)->first();
    }
}

