<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteHistoryRequest;
use App\Http\Requests\DeleteMessagesRequest;
use App\Http\Requests\GetMessagesRequest;
use App\Http\Requests\SendMessageRequest;
use App\Http\Resources\MessageResource;
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
        try{
            $this->messageService->sendMessage($request->only(['username', 'message']));
            return response()->json(['message' => 'Message sended successfully'], 201);
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function deleteMessages(DeleteMessagesRequest $request)
    {
        try{
            $this->messageService->deleteMessagesById($request->only(["ids", "chat_id"]));
            return response()->json(['message' => 'Message deleted successfully'], 200);
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function deleteHistory(DeleteHistoryRequest $request)
    {
        try{
            $this->messageService->deleteHistory($request->only(["chat_id"]));
            return response()->json(['message' => 'History deleted successfully'], 200);
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function getMessages(int $chatId)
    {
        try{
            $messages = $this->messageService->getMessages($chatId);
            return response()->json(['data' => MessageResource::collection($messages)], 200);
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }
}
