<?php namespace Orchestra\Support\Contracts;

interface CsvableInterface
{
    /**
     * Get the instance as an CSV string.
     *
     * @return string
     */
    public function toCsv();
}
