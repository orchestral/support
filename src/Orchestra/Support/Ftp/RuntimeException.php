<?php namespace Orchestra\Support\Ftp;

class RuntimeException extends \RuntimeException {

	protected $parameters = array();

	public function __construct($exception, array $parameters = array())
	{
		$this->parameters = $parameters;
		parent::__construct($exception);
	}
}