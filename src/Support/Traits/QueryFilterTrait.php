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
        $order = Arr::get($input, 'order', '');
        $sort  = Str::upper(Arr::get($input, 'sort', null));

        ! in_array($sort, ['ASC', 'DESC']) && $sort = 'ASC';

        if (in_array($order, ['created', 'updated', 'deleted'])) {
            $order = "{$order}_at";
        }

        ! empty($order) && $query->orderBy($order, $sort);

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
