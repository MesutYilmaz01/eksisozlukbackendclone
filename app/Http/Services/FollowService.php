<?php

namespace App\Http\Services;

use App\Http\RepositoryContracts\IUserRepository;
use App\Http\ServiceContracts\IFollowService;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

class FollowService implements IFollowService
{
    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    public function follow(User $user)
    {
        try {
            if($user->id == request()->user()->id) {
                throw new Exception('An user can not follow itself.', 400);
            }
    
            if(auth()->user()->isUserFollowed($user->id)) {
                throw new Exception('You are already following this user', 400);
            }

            auth()->user()->followed()->attach($user->id);

        }catch (Exception $e) {
            Log::alert(['Follow Service follow method', ['message' => $e->getMessage(), 'code' => $e->getCode()]]);
            throw $e;
        }
    }
}