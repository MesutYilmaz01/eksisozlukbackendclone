<?php

namespace App\Http\Services;

use App\Http\RepositoryContracts\IChatRepository;
use App\Http\RepositoryContracts\IMessageRepository;
use App\Http\RepositoryContracts\IUserRepository;
use App\Http\ServiceContracts\IMessageService;
use App\Models\Message;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class MessageService implements IMessageService
{
    public function __construct(IMessageRepository $messageRepository, IUserRepository $userRepository, IChatRepository $chatRepository)
    {
        $this->messageRepository = $messageRepository;
        $this->userRepository = $userRepository;
        $this->chatRepository = $chatRepository;
    }

    public function sendMessage(array $data): Message 
    {                  
        $user = $this->userRepository->getByUsername($data['username']);
        $chat = $this->chatRepository->firstOrCreate(['first_user_id' => auth()->user()->id , 'second_user_id' => $user->id]);
        
        unset($data["username"]);
        $data["sender_id"] = auth()->user()->id;
        $data["receiver_id"] = $user->id;
        $data["chat_id"] = $chat->id;
        
        $result = $this->messageRepository->store($data);
        if(!$result)
        {
            throw new Exception('An error occured while sending message.', 400);
        }

        return $result;
    }
    
    //public function getMessages(): ?Collection
    //{

    //}
}