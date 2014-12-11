<?php namespace Orchestra\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Fluent;
use Orchestra\Support\Traits\DescendibleTrait;

class Nesty
{
    use DescendibleTrait;

    /**
     * List of items.
     *
     * @var array
     */
    protected $items = [];

    /**
     * Configuration.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Construct a new instance.
     *
     * @param  array  $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * Create a new Fluent instance while appending default config.
     *
     * @param  int  $id
     * @return \Illuminate\Support\Fluent
     */
    protected function toFluent($id)
    {
        $defaults = Arr::get($this->config, 'defaults', []);

        return new Fluent(array_merge($defaults, [
            'id'     => $id,
            'childs' => [],
        ]));
    }

    /**
     * Add item before reference $before.
     *
     * @param  string  $id
     * @param  string  $before
     * @return \Illuminate\Support\Fluent
     */
    protected function addBefore($id, $before)
    {
        $items    = [];
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
     * @param  string  $id
     * @param  string  $after
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
     * @param  string  $id
     * @param  string  $parent
     * @return \Illuminate\Support\Fluent
     */
    protected function addChild($id, $parent)
    {
        $node = $this->descendants($this->items, $parent);

        // it might be possible parent is not defined due to ACL, in this
        // case we should simply ignore this request as child should
        // inherit parent ACL access.
        if (! isset($node)) {
            return null;
        }

        $item = $node->get('childs');
        $item[$id] = $this->toFluent($id);

        $node->childs($item);

        return $item[$id];
    }

    /**
     * Add item as parent.
     *
     * @param  string  $id
     * @return \Illuminate\Support\Fluent
     */
    protected function addParent($id)
    {
        return $this->items[$id] = $this->toFluent($id);
    }

    /**
     * Add a new item, by prepend or append.
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
        $matching = [
            '<' => 'addBefore',
            '>' => 'addAfter',
            '^' => 'addChild',
        ];

        $method = $matching[$key];

        return call_user_func(array($this, $method), $id, $location);
    }

    /**
     * Check whether item by id exists.
     *
     * @param  string  $key
     * @return bool
     */
    public function has($key)
    {
        return ! is_null($this->is($this->items, $key));
    }

    /**
     * Retrieve an item by id.
     *
     * @param  string  $key
     * @return \Illuminate\Support\Fluent
     */
    public function is($key)
    {
        return $this->descendants($this->items, $key);
    }

    /**
     * Return all items.
     *
     * @return array
     */
    public function items()
    {
        return new Collection($this->items);
    }
}
