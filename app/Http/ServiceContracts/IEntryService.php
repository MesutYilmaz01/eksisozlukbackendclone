<?php

namespace App\Http\ServiceContracts;


interface IEntryService
{
    public function sendMessage(array $data);
}