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

    public function getAll(array $data)
    {
        return $this->headerRepository->getAll(isset($data['with']) ? explode(',', $data['with']) : []);
    }

    public function show(string $header)
    {
        return $this->headerRepository->show($header);
    }
}