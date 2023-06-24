<?php

namespace App\Http\Controllers;

use App\Http\Requests\HeaderListRequest;
use App\Http\Resources\Header\HeaderListResource;
use App\Http\Resources\Header\HeaderResource;
use App\Http\ServiceContracts\IHeaderService;

class HeaderController extends Controller
{
    public function __construct(IHeaderService $headerService)
    {
        $this->headerService = $headerService;
    }

    public function getAll(HeaderListRequest $request)
    {
        return HeaderListResource::collection($this->headerService->getAll($request->all()));
    }

    public function show(string $header)
    {   
        return new HeaderResource($this->headerService->show($header));
    }
}
