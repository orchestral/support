<?php namespace Orchestra\Support;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\MessageBag as M;

class Messages extends M {

	/**
	 * Retrieve Message instance from Session, the data should be in
	 * serialize, so we need to unserialize it first.
	 *
	 * @return self
	 */
	public function retrieve()
	{
		$messages = null;
		$instance = null;

		if (Session::has('message'))
		{
			$messages = @unserialize(Session::get('message', ''));
			if (is_array($messages)) $instance = new static($messages);
		}

		Session::forget('message');

		return $instance;
	}

	/**
	 * Store current instance.
	 *
	 * @return void
	 */
	public function save()
	{
		Session::flash('message', $this->serialize());
	}

	/**
	 * Compile the instance into serialize
	 *
	 * @return string   serialize of this instance
	 */
	public function serialize()
	{
		return serialize($this->messages);
	}
}
