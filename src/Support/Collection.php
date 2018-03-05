<?php

namespace Orchestra\Support;

use Orchestra\Support\Contracts\Csvable;
use Orchestra\Contracts\Support\Transformable;
use Illuminate\Support\Collection as BaseCollection;

class Collection extends BaseCollection implements Csvable, Transformable
{
    /**
     * {@inheritdoc}
     */
    public function toCsv()
    {
        ob_start();
        $this->streamCsv();

        return ob_get_clean();
    }

    /**
     * Stream CSV output.
     *
     * @return void
     */
    public function streamCsv()
    {
        $delimiter = ',';
        $enclosure = '"';
        $header = $this->resolveCsvHeader();

        $instance = fopen('php://output', 'r+');

        fputcsv($instance, $header, $delimiter, $enclosure);

        foreach ($this->items as $key => $item) {
            fputcsv($instance, Arr::dot($item), $delimiter, $enclosure);
        }

        fclose($instance);
    }

    /**
     * Resolve CSV header.
     *
     * @return array
     */
    protected function resolveCsvHeader()
    {
        $header = [];

        if (! $this->isEmpty()) {
            $single = $this->first();
            $header = array_keys(Arr::dot($single));
        }

        return $header;
    }
}
