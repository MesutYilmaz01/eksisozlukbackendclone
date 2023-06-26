<?php

namespace App\Http\Controllers;

use App\Http\Requests\FollowRequest;
use App\Http\Resources\User\UserResource;
use App\Http\ServiceContracts\IFollowService;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FollowController extends Controller
{
    public function __construct(IFollowService $followService)
    {
        $this->followService = $followService;
    }

    public function follow(User $user)
    {
        try {
            $this->followService->follow($user);
            return response(['message' => "Following {$user->username} is succesfull."]);
        }catch(Exception $e) {
            Log::alert('FollowController follow method', ['message' => $e->getMessage(), 'code' => $e->getCode()]);
            return response(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function unfollow(User $user)
    {
        try {
            $this->followService->unfollow($user);
            return response(['message' => "Unfollowing {$user->username} is succesfull."]);
        }catch(Exception $e) {
            Log::alert('FollowController unfollow method', ['message' => $e->getMessage(), 'code' => $e->getCode()]);
            return response(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function followers(User $user)
    {
        try {
            return UserResource::collection($this->followService->followers($user));
        }catch(Exception $e) {
            Log::alert('FollowController followers method', ['message' => $e->getMessage(), 'code' => $e->getCode()]);
            return response(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function followed(User $user)
    {
        try {
            return UserResource::collection($this->followService->followed($user));
        }catch(Exception $e) {
            Log::alert('FollowController followed method', ['message' => $e->getMessage(), 'code' => $e->getCode()]);
            return response(['message' => $e->getMessage()], $e->getCode());
        }
    }
}
