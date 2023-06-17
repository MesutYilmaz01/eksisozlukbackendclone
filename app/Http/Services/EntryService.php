<?php

namespace App\Http\Services;

use App\Http\RepositoryContracts\IEntryRepository;
use App\Http\RepositoryContracts\IHeaderRepository;
use App\Http\ServiceContracts\IEntryService;
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
                'message' =>  $data['message']
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
}