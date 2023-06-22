<?php

namespace App\Http\Controllers;

use App\Http\Requests\EntryDeleteRequest;
use App\Http\Requests\EntryStoreRequest;
use App\Http\Requests\EntryUpdateRequest;
use App\Http\ServiceContracts\IEntryService;
use App\Models\Entry;
use Exception;

class EntryController extends Controller
{

    public function __construct(IEntryService $entryService)
    {
        $this->entryService = $entryService;
    }

    public function enterEntry(EntryStoreRequest $request)
    {
        try{
            $this->entryService->sendMessage($request->only(['header_id', 'header', 'message']));
            return response()->json(['message' => 'Entry sended successfully'], 201);
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function deleteEntry(Entry $entry, EntryDeleteRequest $request)
    {
        try{
            $this->entryService->deleteEntry($entry->id);
            return response()->json(['message' => 'Entry deleted successfully'], 201);
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function updateEntry(Entry $entry, EntryUpdateRequest $request)
    {
        try{
            $this->entryService->updateEntry($entry->id, $request->only(['message']));
            return response()->json(['message' => 'Entry updated successfully'], 201);
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }
}
