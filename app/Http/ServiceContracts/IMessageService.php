<?php

namespace App\Http\ServiceContracts;

use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;

interface IMessageService
{
    public function sendMessage(array $data): Message;

    //public function get(int $userOne, int $userTwo): ?Collection;
}