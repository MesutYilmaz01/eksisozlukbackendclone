<?php

namespace App\Http\ServiceContracts;

use App\Models\Chat;

interface IChatService
{
    public function firstOrCreate(array $data): Chat;
}