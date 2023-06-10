<?php

namespace App\Http\Services;

use App\Http\RepositoryContracts\IChatRepository;
use App\Http\ServiceContracts\IChatService;

class ChatService implements IChatService
{
    public function __construct(IChatRepository $ChatRepository)
    {
        $this->chatRepository = $ChatRepository;
    }
}