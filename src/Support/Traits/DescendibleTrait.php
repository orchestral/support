<?php namespace Orchestra\Support\Traits;

trait DescendibleTrait
{
    /**
     * Get last descendant node from items recursively.
     *
     * @param  array   $array
     * @param  string  $key
     *
     * @return \Illuminate\Support\Fluent
     */
    protected function descendants(array $array, $key = null)
    {
        if (is_null($key)) {
            return $array;
        }

        $keys  = explode('.', $key);
        $first = array_shift($keys);

        if (! isset($array[$first])) {
            return;
        }

        return $this->resolveLastDecendant($array[$first], $keys);
    }

    /**
     * Resolve last descendant node from items.
     *
     * @param  array  $array
     * @param  array  $keys
     *
     * @return \Illuminate\Support\Fluent
     */
    protected function resolveLastDecendant($array, $keys)
    {
        $isLastDescendant = function ($array, $segment) {
            return (! is_array($array->childs) || ! isset($array->childs[$segment]));
        };

        // To retrieve the array item using dot syntax, we'll iterate through
        // each segment in the key and look for that value. If it exists,
        // we will return it, otherwise we will set the depth of the array
        // and look for the next segment.
        foreach ($keys as $segment) {
            if ($isLastDescendant($array, $segment)) {
                return $array;
            }

            $array = $array->childs[$segment];
        }

        return $array;
    }
}
