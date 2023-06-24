<?php

namespace App\Traits;

use App\Filters\BaseFilter\QueryFilters;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    public function scopeFilter($query, QueryFilters $filters): Builder
    {
        return $filters->apply($query);
    }
}
