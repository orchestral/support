<?php namespace Orchestra\Support;

use Illuminate\Support\Facades\Session,
	Illuminate\Support\MessageBag as M;

class Messages extends M {

	/**
	 * Retrieve Message instance from Session, the data should be in
	 * serialize, so we need to unserialize it first.
	 *
	 * @access public
	 * @return Messages
	 */
	public function retrieve()
	{
		$messages = null;

		if (Session::has('message'))
		{
			$messages = @unserialize(Session::getFlash('message', ''));
			if (is_array($messages)) $this->messages = $messages;
		}

		Session::forget('message');

		return $this;
	}

	/**
	 * Shudown the message instance.
	 *
	 * @access public
	 * @return void
	 */
	public function shutdown()
	{
		$this->save();
	}
	
	/**
	 * Add a message to the collector.
	 *
	 * <code>
	 *		// Add a message for the e-mail attribute
	 *		$messages->add('email', 'The e-mail address is invalid.');
	 * </code>
	 *
	 * @param  string  $key
	 * @param  string  $message
	 * @return void
	 */
	public function add($key, $message)
	{
		parent::add($key, $message);

		return $this;
	}

	/**
	 * Store current instance.
	 *
	 * @access public
	 * @return void
	 */
	public function save()
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