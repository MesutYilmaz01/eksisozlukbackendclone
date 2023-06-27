<?php

namespace App\Http\Services;

use App\Http\RepositoryContracts\IEntryRepository;
use App\Http\ServiceContracts\ILikeService;
use App\Models\Entry;
use Exception;
use Illuminate\Support\Facades\Log;

class LikeService implements ILikeService
{
    public function __construct(IEntryRepository $entryRepository)
    {
        $this->entryRepository = $entryRepository;
    }

    public function like(Entry $entry)
    {
        
    }
}