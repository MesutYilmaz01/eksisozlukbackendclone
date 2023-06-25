<?php

namespace App\Http\Repositories;

use App\Http\RepositoryContracts\IMessageRepository;
use Illuminate\Database\Eloquent\Collection;

class MessageRepository extends BaseEloquentRepository implements IMessageRepository
{
    public function get(int $userOne, int $userTwo): ?Collection
    {
        return $this->model->query()
                ->where(['sender_id' => $userOne, 'receiver_id' => $userTwo])
                ->orWhere(['sender_id' => $userTwo, 'receiver_id' => $userOne])
                ->get();
    }

    public function updateByIds(array $ids, int $authenticatedUserId)
    {
        foreach($this->model->query()->whereIn('id', $ids)->cursor() as $message) {
            if($message->sender_id == $authenticatedUserId) {
                $message->delete_for_sender = true;
                $message->save();
            }else if($message->receiver_id == $authenticatedUserId) {
                $message->delete_for_receiver = true;
                $message->save();
            }
        }
    }

    public function updateByChatId(int $chatId, int $authenticatedUserId)
    {
        foreach($this->model->query()->where('chat_id', $chatId)->cursor() as $message) {
            if($message->sender_id == $authenticatedUserId) {
                $message->delete_for_sender = true;
                $message->save();
            }else if($message->receiver_id == $authenticatedUserId) {
                $message->delete_for_receiver = true;
                $message->save();
            }
        }
    }

    public function getByChatIdAndUserId(int $chatId, int $authenticatedUserId)
    {
        $messages = collect();
        
        foreach($this->model->query()->where('chat_id', $chatId)->with('sender')->cursor() as $message) {
            if(($message->sender_id == $authenticatedUserId) && $message->delete_for_sender == false) {
                $messages->push($message);
            }else if(($message->receiver_id == $authenticatedUserId) && $message->delete_for_receiver == false) {
                $messages->push($message);
            }
        }
        return $messages;
    }

}