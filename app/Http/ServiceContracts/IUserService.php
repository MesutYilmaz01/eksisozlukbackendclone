<?php

namespace App\Http\ServiceContracts;

use App\Models\User;

interface IUserService
{
    public function store(array $data): User;

    public function changePassword(array $data);
}