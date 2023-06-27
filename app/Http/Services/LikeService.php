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
        try {
            if($entry->isLikeExists(auth()->user()->id)) {
                throw new Exception('This entry already liked by you.', 400);
            }

            $entry->likes()->attach(auth()->user()->id);

        } catch (Exception $e) {
            Log::alert('LikeService like method', ['message' => $e->getMessage(), 'code' => $e->getCode()]);
            throw $e;
        }
    }

    public function dislike(Entry $entry)
    {
        try {
            if(!$entry->isLikeExists(auth()->user()->id)) {
                throw new Exception('This entry is not liked before', 400);
            }

            $entry->likes()->detach(auth()->user()->id);

        } catch (Exception $e) {
            Log::alert('LikeService ublike method', ['message' => $e->getMessage(), 'code' => $e->getCode()]);
            throw $e;
        }
    }
}