<?php

namespace Orchestra\Support;

use Illuminate\Support\Arr as BaseArr;

class Arr extends BaseArr
{
    /**
     * Expand a dotted array. Acts the opposite way of Arr::dot().
     *
     * @param  array  $array
     *
     * @return array
     */
    public static function expand(array $array): array
    {
        $results = [];

        foreach ($array as $key => $value) {
            static::set($results, $key, $value);
        }

        return $results;
    }
}
