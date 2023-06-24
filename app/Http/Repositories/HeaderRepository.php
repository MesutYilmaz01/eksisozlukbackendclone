<?php

namespace App\Http\Repositories;

use App\Http\RepositoryContracts\IHeaderRepository;
use App\Models\Header;


class HeaderRepository extends BaseEloquentRepository implements IHeaderRepository
{

    protected $model = Header::class;

    protected array $relationships = [
        'entries'
    ];
}

