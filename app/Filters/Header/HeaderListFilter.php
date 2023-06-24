<?

namespace App\Filters\Header;

use App\Filters\BaseFilter\QueryFilters;
use Illuminate\Contracts\Database\Eloquent\Builder;

class HeaderListFilter extends QueryFilters
{
    public function filterById($value): Builder
    {
        return $this->builder->where('id', '=', $value);
    }

    public function filterByHeader($value): Builder
    {
        return $this->builder->where('header', 'LIKE', $value);
    }

    public function filterBySlug($value): Builder
    {
        return $this->builder->where('slug', 'LIKE', $value);
    }
}