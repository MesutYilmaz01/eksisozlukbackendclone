<?php

namespace App\Http\Services;

use App\Http\RepositoryContracts\IHeaderRepository;
use App\Http\ServiceContracts\IHeaderService;

class HeaderService implements IHeaderService
{
    public function __construct(IHeaderRepository $headerRepository)
    {
        $this->headerRepository = $headerRepository;
    }

    public function getAll(array $with)
    {
        return $this->headerRepository->getAll($with);
    }
}