<?php

namespace App\Http\Services;

use App\Http\Enums\UserTypeEnums;
use App\Http\RepositoryContracts\IEntryRepository;
use App\Http\RepositoryContracts\IHeaderRepository;
use App\Http\ServiceContracts\IEntryService;
use App\Models\Entry;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EntryService implements IEntryService
{
    public function __construct(IEntryRepository $entryRepository, IHeaderRepository $headerRepository)
    {
        $this->entryRepository = $entryRepository;
        $this->headerRepository = $headerRepository;
    }

    public function sendMessage(array $data)
    {
        try {
            $this->entryRepository->beginTransaction();
            if(!isset($data['header_id'])) {
                
                if(auth()->user()->user_type === UserTypeEnums::NEWBIE) {
                    throw new Exception('Newbies cant create an entry.', 400);
                }
                
                if($this->headerRepository->findByAttributes(['header' => $data['header']])) {
                    throw new Exception('There is already a header with this.', 400);
                }

                $headerData = [
                    'header' => $data['header'],
                    'slug' => str_replace(' ', '-', strtolower($data['header'])),
                    'created_by' => auth()->user()->id
                ];

                $header = $this->headerRepository->create($headerData);

                $data['header_id'] = $header->id;

                if(!$header) {
                    throw new Exception('An error ocured while adding header.', 400);
                }
            }

            $storeData = [
                'header_id' => $data['header_id'],
                'user_id' => auth()->user()->id,
                'message' =>  $data['message'],
                'user_type' => auth()->user()->user_type
            ];

            if(!$this->entryRepository->create($storeData)) {
                throw new Exception('An error ocured while adding entry.', 400);
            }
            $this->entryRepository->commit();

        } catch(Exception $e) {
            $this->entryRepository->rollback();
            Log::alert('EntryService sendMessage method', ['message' => $e->getMessage(), 'code' => $e->getCode()]);
            throw $e;
        }
    }

    public function deleteEntry(Entry $entry)
    {
        if(!$this->entryRepository->delete($entry)) {
            throw new Exception('An error occured while deleting Entry', 400);
        }
    }

    public function updateEntry(Entry $entry, array $data)
    {
        if(!$this->entryRepository->update($entry, $data)) {
            throw new Exception('An error occured while updating Entry', 400);
        }
    }
}