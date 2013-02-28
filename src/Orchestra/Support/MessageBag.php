<?php namespace Orchestra\Support;

use Illuminate\Support\Facades\Session,
	Illuminate\Support\MessageBag as M;

class MessageBag extends M {

	/**
	 * Add a message to the collector.
	 *
	 * <code>
	 *		// Add a message for the e-mail attribute
	 *		MessageBag::make('email', 'The e-mail address is invalid.');
	 * </code>
	 *
	 * @static
	 * @param  string  $key
	 * @param  string  $message
	 * @return void
	 */
	public static function make($key, $message)
	{
		$instance = new static();
		$instance->add($key, $message);

		return $instance;
	}

	/**
	 * Retrieve Message instance from Session, the data should be in
	 * serialize, so we need to unserialize it first.
	 *
	 * @static
	 * @access public
	 * @return Messages
	 */
	public static function getSessionFlash()
	{
		$message = null;

		if (Session::has('message'))
		{
			$message = @unserialize(Session::getFlash('message', ''));
		}

		Session::forget('message');

		return new static($message);
	}

	/**
	 * Store current instance.
	 *
	 * @access public
	 * @return void
	 */
	public function store()
	{
		Session::flash('message', $this->serialize());
	}

	/**
	 * Compile the instance into serialize
	 *
	 * @access public
	 * @return string   serialize of this instance
	 */
	public function serialize()
	{
		return serialize($this);
	}
}
