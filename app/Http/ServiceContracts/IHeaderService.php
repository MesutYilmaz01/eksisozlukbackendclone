<?php

namespace App\Http\ServiceContracts;


interface IHeaderService
{
    public function getAll(array $with);
}