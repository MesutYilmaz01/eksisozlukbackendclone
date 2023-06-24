<?php

namespace App\Http\Services;

use App\Filters\Header\HeaderListFilter;
use App\Http\RepositoryContracts\IHeaderRepository;
use App\Http\ServiceContracts\IHeaderService;

class HeaderService implements IHeaderService
{
    public function __construct(IHeaderRepository $headerRepository)
    {
        $this->headerRepository = $headerRepository;
    }

    public function getAll(array $params)
    {
        $headerFilter = app(HeaderListFilter::class);
        $headerFilter->setFilters($params);

        $this->headerRepository->parseRequest($params);

        return $this->headerRepository
                ->with(['entries'])
                ->withFilters($headerFilter)
                ->getAll(['*']);
    }

    public function show(string $header)
    {
        return $this->headerRepository->findByAttributes(['slug' => $header]);
    }
}