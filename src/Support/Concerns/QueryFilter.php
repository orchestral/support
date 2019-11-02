<?php

namespace Orchestra\Support\Concerns;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Expression;
use Orchestra\Support\Str;

trait QueryFilter
{
    /**
     * Setup basic query string filter to eloquent or query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @param  array  $input
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    protected function sortQueryUsing($query, array $input = [])
    {
        $orderBy = $this->sortQueryOrderedBy($input);

        $direction = $this->sortQueryDirection($input);

        $columns = $input['columns'] ?? null;

        if (\is_array($columns) && $this->isColumnExcludedFromFilter($orderBy, $columns)) {
            return $query;
        }

        ! empty($orderBy) && $query->orderBy($orderBy, $direction);

        return $query;
    }

    /**
     * Setup wildcard query string filter to eloquent or query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @param  string|null  $keyword
     * @param  array  $fields
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    protected function filterQueryUsing($query, ?string $keyword, array $fields)
    {
        if (! empty($keyword) && ! empty($fields)) {
            $query->where(function ($query) use ($fields, $keyword) {
                $this->buildFilteredQuery($query, $fields, Str::searchable($keyword));
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
    protected function isColumnExcludedFromFilter(string $on, array $columns = []): bool
    {
        $only = $columns['only'] ?? '';
        $except = $columns['except'] ?? '';

        return (! empty($only) && ! \in_array($on, (array) $only)) ||
            (! empty($except) && \in_array($on, (array) $except));
    }

    /**
     * Get basic query direction value (either ASC or DESC).
     *
     * @param  array  $input
     *
     * @return string
     */
    protected function sortQueryDirection(array $input): string
    {
        $direction = Str::upper($input['direction'] ?? '');

        if (\in_array($direction, ['ASC', 'DESC'])) {
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
    protected function sortQueryOrderedBy(array $input): string
    {
        $orderBy = $input['order_by'] ?? '';

        if (\in_array($orderBy, ['created', 'updated', 'deleted'])) {
            $orderBy = "{$orderBy}_at";
        }

        return $orderBy;
    }

    /**
     * Build wildcard query filters.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @param  array  $fields
     * @param  array  $keyword
     * @param  string  $group
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    protected function buildFilteredQuery($query, array $fields, array $keyword = [])
    {
        $connectionType = $query instanceof EloquentBuilder
            ? $query->getModel()->getConnection()->getDriverName()
            : $query->getConnection()->getDriverName();

        $likeOperator = $connectionType == 'pgsql' ? 'ilike' : 'like';

        foreach ($fields as $field) {
            $this->filterQueryOnColumn($query, $field, $keyword, $likeOperator);
        }

        return $query;
    }

    /**
     * Build wildcard query filter for field using where or orWhere.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @param  \Illuminate\Database\Query\Expression|string  $column
     * @param  array  $keyword
     * @param  string  $likeOperator
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    protected function filterQueryOnColumn($query, $column, array $keyword, string $likeOperator = 'like')
    {
        if ($column instanceof Expression) {
            return $this->filterQueryOnColumnUsing($query, $column->getValue(), $keyword, $likeOperator, 'orWhere');
        } elseif (! (Str::contains($column, '.') && $query instanceof EloquentBuilder)) {
            return $this->filterQueryOnColumnUsing($query, $column, $keyword, $likeOperator, 'orWhere');
        }

        $this->filterQueryOnColumnUsing($query, $column, $keyword, $likeOperator, 'orWhere');
        [$relation, $column] = \explode('.', $column, 2);

        return $query->orWhereHas($relation, function ($query) use ($column, $keyword) {
            $this->filterQueryOnColumnUsing($query, $column, $keyword, $likeOperator, 'where');
        });
    }

    /**
     * Build wildcard query filter for column using where or orWhere.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @param  string  $column
     * @param  array  $keyword
     * @param  string  $likeOperator
     * @param  string  $whereOperator
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    protected function filterQueryOnColumnUsing(
        $query,
        string $column,
        array $keyword = [],
        string $likeOperator,
        string $whereOperator = 'where'
    ) {
        Str::contains($column, '->');

        $callback = static function ($query) use ($column, $keyword, $likeOperator) {
            foreach ($keyword as $key) {
                $query->orWhere($column, $likeOperator, $key);
            }
        };

        return $query->{$whereOperator}($callback);
    }


    /**
     * Setup basic query string filter to eloquent or query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @param  array  $input
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     *
     * @deprecated v4.x
     */
    protected function setupBasicQueryFilter($query, array $input = [])
    {
        return $this->sortQueryUsing($query, $input);
    }

    /**
     * Setup wildcard query string filter to eloquent or query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @param  string|null  $keyword
     * @param  array  $fields
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     *
     * @deprecated v4.x
     */
    protected function setupWildcardQueryFilter($query, ?string $keyword, array $fields)
    {
        return $this->filterQueryUsing($query, $keyword, $fields);
    }
}
