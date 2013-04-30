<?php namespace Orchestra\Support;

use Illuminate\Support\Fluent;

class Nesty {

	/**
	 * List of items
	 *
	 * @access  protected
	 * @var     array
	 */
	protected $items = array();

	/**
	 * Configuration
	 *
	 * @access  protected
	 * @var     array
	 */
	protected $config = array();

	/**
	 * Construct a new instance
	 *
	 * @access public
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
	 * @access protected
	 * @param  int  $id
	 * @return Illuminate\Support\Fluent
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
	 * Add item before reference $before
	 *
	 * @static
	 * @access protected
	 * @param  string   $id
	 * @param  string   $before
	 * @return Illuminate\Support\Fluent
	 */
	protected function addBefore($id, $before)
	{
		$items = array();
		$found = false;
		$item  = $this->toFluent($id);

		$keys     = array_keys($this->items);
		$position = array_search($before, $keys);

		if (is_null($position)) return $this->add_parent($id);

		if ($position > 0) $position--;

		foreach ($keys as $key => $fluent)
		{
			if ($key === $position)
			{
				$found      = true;
				$items[$id] = $item;
			}

			$items[$fluent] = $this->items[$fluent];
		}

		if ( ! $found) $items[$id] = $item;

		$this->items = $items;

		return $item;
	}

	/**
	 * Add item after reference $after
	 *
	 * @access protected
	 * @param  string   $id
	 * @param  string   $after
	 * @return Fluent
	 */
	protected function addAfter($id, $after)
	{
		$found = false;
		$items = array();
		$item  = $this->toFluent($id);

		$keys     = array_keys($this->items);
		$position = array_search($after, $keys);

		if (is_null($position)) return $this->add_parent($id);

		$position++;

		foreach ($keys as $key => $fluent)
		{
			if ($key === $position)
			{
				$found      = true;
				$items[$id] = $item;
			}

			$items[$fluent] = $this->items[$fluent];
		}

		if ( ! $found) $items[$id] = $item;

		$this->items = $items;

		return $item;
	}

	/**
	 * Add item as child of $parent
	 *
	 * @access protected
	 * @param  string   $id
	 * @param  string   $parent
	 * @return Illuminate\Support\Fluent
	 */
	protected function addChild($id, $parent)
	{
		$node = $this->descendants($parent);

		// it might be possible parent is not defined due to ACL, in this
		// case we should simply ignore this request as child should
		// inherit parent ACL access
		if ( ! isset($node)) return null;

		$item = $node->childs;
		$item[$id] = $this->toFluent($id);

		$node->childs($item);

		return $item[$id];
	}

	/**
	 * Add item as parent
	 *
	 * @access protected
	 * @param  string   $id
	 * @return Fluent
	 */
	protected function addParent($id)
	{
		return $this->items[$id] = $this->toFluent($id);
	}

	/**
	 * Add a new item, prepending or appending
	 *
	 * @access  public
	 * @param   string  $id
	 * @param   string  $prepend
	 * @return  self
	 */
	public function add($id, $location = '#')
	{
		preg_match('/^(<|>|\^):(.+)$/', $location, $matches);

		switch (true)
		{
			case count($matches) >= 3 and $matches[1] === '<' :
				return $this->addBefore($id, $matches[2]);
				break;

			case count($matches) >= 3 and $matches[1] === '>' :
				return $this->addAfter($id, $matches[2]);
				break;

			case count($matches) >= 3 and $matches[1] === '^' :
				return $this->addChild($id, $matches[2]);
				break;

			default :
				return $this->addParent($id);
				break;
		}
	}

	/**
	 * Get node from items recursively
	 * 
	 * @access protected
	 * @param  string       $key
	 * @return Fluent
	 */
	protected function descendants($key)
	{
		$array = $this->items;

		if (is_null($key)) return $array;

		$keys  = explode('.', $key);
		$first = array_shift($keys);

		if ( ! isset($array[$first])) return null;

		$array = $array[$first];

		// To retrieve the array item using dot syntax, we'll iterate through
		// each segment in the key and look for that value. If it exists,
		// we will return it, otherwise we will set the depth of the array
		// and look for the next segment.
		foreach ($keys as $segment)
		{
			if ( ! is_array($array->childs) or ! isset($array->childs[$segment]))
			{
				return $array;
			}

			$array = $array->childs[$segment];
		}

		return $array;
	}

	/**
	 * Return all items
	 *
	 * @access public
	 * @return array
	 */
	public function getItems()
	{
		return $this->items;
	}
}
