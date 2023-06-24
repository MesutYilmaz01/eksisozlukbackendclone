<?php

namespace App\Http\ServiceContracts;


interface IHeaderService
{
    public function getAll(array $params);

    public function show(string $header);
}