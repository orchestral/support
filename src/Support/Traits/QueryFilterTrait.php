<?php namespace Orchestra\Support\Traits;

use Orchestra\Support\Str;

trait QueryFilterTrait
{
    /**
     * Setup basic query string filter to eloquent or query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @param  array  $input
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    protected function setupBasicQueryFilter($query, array $input = [])
    {
        $orderBy = $this->getBasicQueryOrderBy($input);

        $direction = $this->getBasicQueryDirection($input);

        $columns = isset($input['columns']) ? $input['columns'] : null;

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
     *
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
     *
     * @return bool
     */
    protected function isColumnExcludedFromFilterable($on, array $columns = [])
    {
        $only   = isset($columns['only']) ? $columns['only'] : '';
        $except = isset($columns['except']) ? $columns['except'] : '';

        return ((! empty($only) && ! in_array($on, (array) $only)) ||
            (! empty($except) && in_array($on, (array) $except)));
    }

    /**
     * Get basic query direction value (either ASC or DESC).
     *
     * @param  array  $input
     *
     * @return string
     */
    protected function getBasicQueryDirection(array $input)
    {
        $direction = Str::upper(isset($input['direction']) ? $input['direction'] : '');

        if (in_array($direction, ['ASC', 'DESC'])) {
            return $direction;
        }

        return 'ASC';
    }

    /**
     * Get basic query order by column.
     *
     * @param  array  $input
     *
     * @return string
     */
    protected function getBasicQueryOrderBy(array $input)
    {
        $orderBy = isset($input['order_by']) ? $input['order_by'] : '';

        if (in_array($orderBy, ['created', 'updated', 'deleted'])) {
            $orderBy = "{$orderBy}_at";
        }

        return $orderBy;
    }
}
