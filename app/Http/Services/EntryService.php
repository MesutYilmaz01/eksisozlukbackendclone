<?php

namespace App\Http\Services;

use App\Http\Enums\UserTypeEnums;
use App\Http\RepositoryContracts\IEntryRepository;
use App\Http\RepositoryContracts\IHeaderRepository;
use App\Http\ServiceContracts\IEntryService;
use App\Models\Entry;
use Exception;
use Illuminate\Support\Facades\DB;

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
            DB::beginTransaction();
            if(!isset($data['header_id'])) {
                
                if(auth()->user()->user_type === UserTypeEnums::NEWBIE) {
                    throw new Exception('Newbies cant create an entry.', 400);
                }

                if($this->headerRepository->getByHeader($data['header'])) {
                    throw new Exception('There is already a header with this.', 400);
                }

                $headerData = [
                    'header' => $data['header'],
                    'created_by' => auth()->user()->id
                ];

                $header = $this->headerRepository->store($headerData);

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

            if(!$this->entryRepository->store($storeData)) {
                throw new Exception('An error ocured while adding entry.', 400);
            }
            DB::commit();

        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteEntry(int $id)
    {
        
        $entry = $this->getEntryById($id);

        $this->checkIsOwner($entry);

        $result = $this->entryRepository->deleteById($id);

        if(!$result) {
            throw new Exception('An error occured while deleting Entry', 400);
        }
        
    }

    public function updateEntry(int $id, array $data)
    {
        $entry = $this->getEntryById($id);

        $this->checkIsOwner($entry);

        $result = $this->entryRepository->updateById($id, $data);

        if(!$result) {
            throw new Exception('An error occured while updating Entry', 400);
        }
    }

    private function getEntryById(int $id): ?Entry
    {
        $entry = $this->entryRepository->getById($id);

        if(!$entry) {
            throw new Exception('There is not any id with this id', 400);
        }

        return $entry;
    }

    private function checkIsOwner(Entry $entry)
    {
        if($entry->user_id != auth()->user()->id)
        {
            throw new Exception('You are not owner of this entry', 400);
        }
    }
}