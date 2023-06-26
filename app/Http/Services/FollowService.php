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
            throw $e;
        }
    }

    public function unfollow(User $user)
    {
        try {
            if($user->id == request()->user()->id) {
                throw new Exception('An user can not unfollow itself.', 400);
            }
    
            if(!auth()->user()->isUserFollowed($user->id)) {
                throw new Exception('You are already not following this user', 400);
            }

            auth()->user()->followed()->detach($user->id);

        }catch (Exception $e) {
            throw $e;
        }
    }

    public function followers(User $user)
    {
        try {
           return $user->followers;
        }catch (Exception $e) {
            throw $e;
        }
    }

    public function followed(User $user)
    {
        try {
           return $user->followed;
        }catch (Exception $e) {
            throw $e;
        }
    }
}