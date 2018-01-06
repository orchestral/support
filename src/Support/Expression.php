<?php

namespace Orchestra\Support;

use Illuminate\Contracts\Support\Htmlable;

class Expression implements Htmlable
{
    /**
     * The value of the expression.
     *
     * @var string
     */
    protected $value;

    /**
     * Create a new expression instance.
     *
     * @param  string  $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * Get the string value of the expression.
     *
     * @return string
     */
    public function get(): string
    {
        return $this->value;
    }

    /**
     * Get content as a string of HTML.
     *
     * @return string
     */
    public function toHtml(): string
    {
        return $this->get();
    }

    /**
     * Get the string value of the expression.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->get();
    }
}
