<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetMessagesRequest;
use App\Http\Requests\SendMessageRequest;
use App\Http\ServiceContracts\IMessageService;
use Exception;

class MessageController extends Controller
{
    public function __construct(IMessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    public function sendMessage(SendMessageRequest $request)
    {
        $this->messageService->sendMessage($request->only(['username', 'message']));

        try{
            return response()->json(['message' => 'Message sended successfully'], 201);
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    //public function getMessages(GetMessagesRequest $request)
    //{
    //    try{
    //        $this->service->changePassword($request->only(['old_password', 'new_password']));
    //        return response()->json(['message' => 'Password changed successfully'], 201);
    //    }catch(Exception $e){
    //        return response()->json(['message' => $e->getMessage()], $e->getCode());
    //    }
    //}
}
