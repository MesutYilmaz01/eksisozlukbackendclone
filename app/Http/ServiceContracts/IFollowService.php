<?php

namespace App\Http\ServiceContracts;

use App\Models\User;

interface IFollowService
{
    public function follow(User $user);
}