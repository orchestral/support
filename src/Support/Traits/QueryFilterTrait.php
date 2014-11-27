<?php namespace Orchestra\Support\Traits;

use Illuminate\Support\Arr;
use Orchestra\Support\Str;

trait QueryFilterTrait
{
    /**
     * Setup basic query string filter to eloquent or query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder    $query
     * @param  array                                                                       $input
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    protected function setupBasicQueryFilter($query, array $input = array())
    {
        $orderBy = Arr::first($input, function ($key) {
            return in_array($key, ['order_by', 'order']);
        }, '');

        $direction = Str::upper(Arr::first($input, function ($key) {
            return in_array($key, ['direction', 'sort']);
        }, ''));

        $columns = $input['columns'];

        ! in_array($direction, ['ASC', 'DESC']) && $direction = 'ASC';

        if (in_array($orderBy, ['created', 'updated', 'deleted'])) {
            $orderBy = "{$orderBy}_at";
        }

        ! empty($orderBy)  && in_array($orderBy, $columns) && $query->orderBy($orderBy, $direction);

        return $query;
    }

    /**
     * Setup wildcard query string filter to eloquent or query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder    $query
     * @param  mixed                                                                       $keyword
     * @param  array                                                                       $fields
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
}
