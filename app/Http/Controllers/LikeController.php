<?php

namespace App\Http\Controllers;

use App\Http\ServiceContracts\ILikeService;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function __construct(ILikeService $likeService)
    {
        $this->likeService = $likeService;
    }
}
