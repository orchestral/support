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
        return stream_get_contents($this->streamCsv());
    }

    /**
     * Stream CSV output.
     *
     * @return object
     */
    public function streamCsv()
    {
        $delimiter = ',';
        $enclosure = '"';
        $header = $this->resolveCsvHeader();

        $stream = fopen('php://output', 'r+');

        fputcsv($stream, $header, $delimiter, $enclosure);

        foreach ($this->items as $key => $item) {
            fputcsv($stream, Arr::dot($item), $delimiter, $enclosure);
        }

        rewind($stream);

        return $stream;
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
