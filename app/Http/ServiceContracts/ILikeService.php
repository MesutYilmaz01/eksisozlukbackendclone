<?php

namespace App\Http\ServiceContracts;

use App\Models\Entry;

interface ILikeService
{
    public function like(Entry $entry);
}