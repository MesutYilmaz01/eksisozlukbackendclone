<?php

namespace App\Filters\BaseFilter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Class QueryFilters
 * @package App\Filters\BaseFilter
 *
 * @SuppressWarnings(PHPMD)
 */
class QueryFilters
{
    protected array $filterArray;
    protected Builder $builder;

    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;
        foreach ($this->filters() as $name => $value) {
            $method = "filterBy".Str::studly($name);
            if (! method_exists($this, $method)) {
                continue;
            }

            if (strlen($value)) {
                $this->$method($value);
                continue;
            }

            $this->$method();
        }

        $this->mandatoryFilters();
        return $this->builder;
    }

    public function filters(): array
    {
        return $this->filterArray;
    }

    public function setFilters(array $filterArray = []): void
    {
        $this->filterArray = $filterArray;
    }

    protected function mandatoryFilters(): ?Builder
    {
        return null;
    }
}
