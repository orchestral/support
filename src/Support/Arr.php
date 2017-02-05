<?php

namespace Orchestra\Support;

use Illuminate\Support\Arr as BaseArr;

class Arr extends BaseArr
{
    /**
     * Expand a dotted array. Acts the opposite way of Arr::dot().
     *
     * @param  array  $array
     * @param  int  $depth
     * @return array
     */
    public static function expand($array, $depth = INF)
    {
        $results = [];

        foreach ($array as $key => $value) {
            if (count($dottedKeys = explode('.', $key, 2)) > 1) {
                $results[$dottedKeys[0]][$dottedKeys[1]] = $value;
            } else {
                $results[$key] = $value;
            }
        }

        foreach ($results as $key => $value) {
            if (is_array($value) && ! empty($value) && $depth > 1) {
                $results[$key] = static::expand($value, $depth - 1);
            }
        }

        return $results;
    }
}
