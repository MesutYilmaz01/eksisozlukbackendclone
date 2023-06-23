<?php

namespace App\Http\Controllers;

use App\Http\Resources\Header\HeaderListResource;
use App\Http\ServiceContracts\IHeaderService;
use Illuminate\Http\Request;

class HeaderController extends Controller
{
    public function __construct(IHeaderService $headerService)
    {
        $this->headerService = $headerService;
    }

    public function getAll(Request $request)
    {
        return HeaderListResource::collection($this->headerService->getAll($request->only('with')));
    }
}
