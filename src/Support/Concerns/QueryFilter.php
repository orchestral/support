<?php

namespace Orchestra\Support\Concerns;

use Orchestra\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;

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
    protected function setupBasicQueryFilter($query, array $input = [])
    {
        $orderBy = $this->getBasicQueryOrderBy($input);

        $direction = $this->getBasicQueryDirection($input);

        $columns = $input['columns'] ?? null;

        if (\is_array($columns) && $this->isColumnExcludedFromFilterable($orderBy, $columns)) {
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
    protected function setupWildcardQueryFilter($query, ?string $keyword, array $fields)
    {
        if (! empty($keyword) && ! empty($fields)) {
            $query->where(function ($query) use ($fields, $keyword) {
                $this->buildWildcardQueryFilters($query, $fields, Str::searchable($keyword));
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
    protected function isColumnExcludedFromFilterable(string $on, array $columns = []): bool
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
    protected function getBasicQueryDirection(array $input): string
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
    protected function getBasicQueryOrderBy(array $input): string
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
    protected function buildWildcardQueryFilters($query, array $fields, array $keyword = [])
    {
        foreach ($fields as $field) {
            $this->buildWildcardForField($query, $field, $keyword);
        }

        return $query;
    }

    /**
     * Build wildcard query filter for field using where or orWhere.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @param  \Illuminate\Database\Query\Expression|string  $field
     * @param  array  $keyword
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    protected function buildWildcardForField($query, $field, array $keyword)
    {
        if ($field instanceof Expression) {
            return $this->buildWildcardForFieldUsing($query, $field->getValue(), $keyword, 'orWhere');
        } elseif (! (Str::contains($field, '.') && $query instanceof Builder)) {
            return $this->buildWildcardForFieldUsing($query, $field, $keyword, 'orWhere');
        }

        $this->buildWildcardForFieldUsing($query, $field, $keyword, 'orWhere');
        list($relation, $field) = \explode('.', $field, 2);

        return $query->orWhereHas($relation, function ($query) use ($field, $keyword) {
            $this->buildWildcardForFieldUsing($query, $field, $keyword, 'where');
        });
    }

    /**
     * Build wildcard query filter for field using where or orWhere.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @param  string  $field
     * @param  array  $keyword
     * @param  string  $group
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    protected function buildWildcardForFieldUsing($query, string $field, array $keyword = [], string $group = 'where')
    {
        $callback = function ($query) use ($field, $keyword) {
            foreach ($keyword as $key) {
                $query->orWhere($field, 'LIKE', $key);
            }
        };

        return $query->{$group}($callback);
    }
}
