<?php namespace Orchestra\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;

class Nesty
{
    /**
     * List of items.
     *
     * @var array
     */
    protected $items = array();

    /**
     * Configuration.
     *
     * @var array
     */
    protected $config = array();

    /**
     * Construct a new instance.
     *
     * @param  array    $config
     * @return void
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Create a new Fluent instance while appending default config.
     *
     * @param  integer  $id
     * @return \Illuminate\Support\Fluent
     */
    protected function toFluent($id)
    {
        $defaults = isset($this->config['defaults']) ?
            $this->config['defaults'] : array();

        return new Fluent(array_merge($defaults, array(
            'id'     => $id,
            'childs' => array(),
        )));
    }

    /**
     * Add item before reference $before.
     *
     * @param  string   $id
     * @param  string   $before
     * @return \Illuminate\Support\Fluent
     */
    protected function addBefore($id, $before)
    {
        $items    = array();
        $item     = $this->toFluent($id);
        $keys     = array_keys($this->items);
        $position = array_search($before, $keys);

        if ($position === false) {
            return $this->addParent($id);
        }

        foreach ($keys as $key => $fluent) {
            if ($key === $position) {
                $items[$id] = $item;
            }

            $items[$fluent] = $this->items[$fluent];
        }

        $this->items = $items;

        return $item;
    }

    /**
     * Add item after reference $after.
     *
     * @param  string   $id
     * @param  string   $after
     * @return \Illuminate\Support\Fluent
     */
    protected function addAfter($id, $after)
    {
        $items    = array();
        $item     = $this->toFluent($id);
        $keys     = array_keys($this->items);
        $position = array_search($after, $keys);

        if ($position === false) {
            return $this->addParent($id);
        }

        foreach ($keys as $key => $fluent) {
            $items[$fluent] = $this->items[$fluent];

            if ($key === $position) {
                $items[$id] = $item;
            }
        }

        $this->items = $items;

        return $item;
    }

    /**
     * Add item as child of $parent.
     *
     * @param  string   $id
     * @param  string   $parent
     * @return \Illuminate\Support\Fluent
     */
    protected function addChild($id, $parent)
    {
        $node = $this->descendants($parent);

        // it might be possible parent is not defined due to ACL, in this
        // case we should simply ignore this request as child should
        // inherit parent ACL access.
        if (! isset($node)) {
            return null;
        }

        $item = $node->childs;
        $item[$id] = $this->toFluent($id);

        $node->childs($item);

        return $item[$id];
    }

    /**
     * Add item as parent.
     *
     * @param  string   $id
     * @return \Illuminate\Support\Fluent
     */
    protected function addParent($id)
    {
        return $this->items[$id] = $this->toFluent($id);
    }

    /**
     * Add a new item, prepending or appending
     *
     * @param  string  $id
     * @param  string  $location
     * @return \Illuminate\Support\Fluent
     */
    public function add($id, $location = '#')
    {
        if ($location === '<' && count($keys = array_keys($this->items)) > 0) {
            return $this->addBefore($id, $keys[0]);
        } elseif (preg_match('/^(<|>|\^):(.+)$/', $location, $matches) && count($matches) >= 3) {
            return $this->pickTraverseFromMatchedExpression($id, $matches[1], $matches[2]);
        }

        return $this->addParent($id);
    }

    /**
     * Pick traverse from matched expression.
     *
     * @param  string  $id
     * @param  string  $key
     * @param  string  $location
     * @return \Illuminate\Support\Fluent
     */
    protected function pickTraverseFromMatchedExpression($id, $key, $location)
    {
        $matching = array(
            '<' => 'addBefore',
            '>' => 'addAfter',
            '^' => 'addChild',
        );

        $method = $matching[$key];

        return call_user_func(array($this, $method), $id, $location);
    }

    /**
     * Retrieve an item by id.
     *
     * @param  string   $key
     * @return \Illuminate\Support\Fluent
     */
    public function is($key)
    {
        return $this->descendants($key);
    }

    /**
     * Get last descendant node from items recursively.
     *
     * @param  string   $key
     * @return \Illuminate\Support\Fluent
     */
    protected function descendants($key = null)
    {
        $array = $this->items;

        if (is_null($key)) {
            return $array;
        }

        $keys  = explode('.', $key);
        $first = array_shift($keys);

        if (! isset($array[$first])) {
            return null;
        }

        return $this->resolveLastDecendant($array[$first], $keys);
    }

    /**
     * Resolve last descendant node from items.
     *
     * @param  array    $array
     * @param  array    $key
     * @return \Illuminate\Support\Fluent
     */
    protected function resolveLastDecendant($array, $keys)
    {
        $isLastDescendant = function ($array, $segment) {
            return ( ! is_array($array->childs) || ! isset($array->childs[$segment]));
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

    /**
     * Return all items.
     *
     * @return array
     */
    public function getItems()
    {
        return new Collection($this->items);
    }
}
