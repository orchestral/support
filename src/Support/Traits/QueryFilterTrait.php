<?php namespace Orchestra\Support\Traits;

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
        $order = array_get($input, 'order', null);
        $sort  = strtoupper(array_get($input, 'sort', ''));

        ! in_array($sort, ['ASC', 'DESC']) && $sort = 'ASC';

        if (in_array($order, ['created', 'updated', 'deleted'])) {
            $query->orderBy("{$order}_at", $sort);
        }

        return $query;
    }
}
