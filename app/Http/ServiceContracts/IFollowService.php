<?php

namespace App\Http\ServiceContracts;

use App\Models\User;

interface IFollowService
{
    public function follow(User $user);

    public function unfollow(User $user);

    public function followers(User $user);

    public function followed(User $user);
}