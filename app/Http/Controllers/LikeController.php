<?php

namespace App\Http\Controllers;

use App\Http\ServiceContracts\ILikeService;
use App\Models\Entry;
use Exception;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function __construct(ILikeService $likeService)
    {
        $this->likeService = $likeService;
    }

    public function like(Entry $entry)
    {
        try{
            $this->likeService->like($entry);
            return response(['message' => "Entry liked"]);
        } catch (Exception $e) {
            return response(['message' => $e->getMessage()], $e->getCode());
        }
    }
}
