<?php namespace Orchestra\Support\Traits;

use Orchestra\Support\Str;
use Illuminate\Support\Arr;

trait QueryFilterTrait
{
    /**
     * Setup basic query string filter to eloquent or query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @param  array  $input
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    protected function setupBasicQueryFilter($query, array $input = [])
    {
        $orderBy = Arr::get($input, 'order_by', '');

        $direction = Str::upper(Arr::get($input, 'direction', ''));

        ! in_array($direction, ['ASC', 'DESC']) && $direction = 'ASC';

        if (in_array($orderBy, ['created', 'updated', 'deleted'])) {
            $orderBy = "{$orderBy}_at";
        }

        $columns = Arr::get($input, 'columns');

        if (is_array($columns) && $this->isColumnExcludedFromFilterable($orderBy, $columns)) {
            return $query;
        }

        ! empty($orderBy) && $query->orderBy($orderBy, $direction);

        return $query;
    }

    /**
     * Setup wildcard query string filter to eloquent or query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @param  mixed  $keyword
     * @param  array  $fields
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    protected function setupWildcardQueryFilter($query, $keyword, array $fields)
    {
        if (! empty($keyword)) {
            $query->where(function ($query) use ($keyword, $fields) {
                $keyword = Str::searchable($keyword);

                foreach ($keyword as $key) {
                    foreach ($fields as $field) {
                        $query->orWhere($field, 'LIKE', $key);
                    }
                }
            });
        }

        return $query;
    }

    /**
     * Check if column can be filtered for query.
     *
     * @param  string  $on
     * @param  array   $columns
     * @return bool
     */
    protected function isColumnExcludedFromFilterable($on, array $columns = [])
    {
        $only = Arr::get($columns, 'only', []);
        $except = Arr::get($columns, 'except', []);

        return ((! empty($only) && ! in_array($on, (array) $only)) ||
            (! empty($except) && in_array($on, (array) $except)));
    }
}
