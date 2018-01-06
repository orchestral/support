<?php

namespace Orchestra\Support\Contracts;

interface Csvable
{
    /**
     * Get the instance as an CSV string.
     *
     * @return string
     */
    public function toCsv(): string;
}
