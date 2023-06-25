<?php

namespace App\Http\Repositories;

use App\Http\RepositoryContracts\IChatRepository;
use App\Models\Chat;

class ChatRepository extends BaseEloquentRepository implements IChatRepository
{
    protected $model = Chat::class;
    
    public function getFirstOrCreate(array $data): Chat
    {
        $isExists = $this->model->query()
            ->where(
                [
                    'first_user_id' => $data['first_user_id'],
                    'second_user_id' => $data['second_user_id']
                ])
            ->orWhere(
                [
                    'first_user_id' => $data['second_user_id'],
                    'second_user_id' => $data['first_user_id']
                ])
            ->first();

        if($isExists) {
            return $isExists;
        }

        return parent::create([
            'first_user_id' => $data['first_user_id'], 
            'second_user_id' => $data['second_user_id']
        ]);
    }
}

