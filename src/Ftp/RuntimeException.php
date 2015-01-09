<?php namespace Orchestra\Support\Ftp;

class RuntimeException extends \RuntimeException
{

    /**
     * Parameters.
     *
     * @var array
     */
    protected $parameters = [];

    /**
     * Construct a new exception.
     *
     * @param  string  $exception
     * @param  array   $parameters
     */
    public function __construct($exception, array $parameters = [])
    {
        $this->parameters = $parameters;

        parent::__construct($exception);
    }

    /**
     * Get exceptions parameters.
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}
