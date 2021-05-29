<?php

namespace Orchestra\Support;

use Illuminate\Support\Collection as BaseCollection;
use Orchestra\Contracts\Support\Transformable;
use Orchestra\Support\Contracts\Csvable;

class Collection extends BaseCollection implements Csvable, Transformable
{
    /**
     * {@inheritdoc}
     */
    public function toCsv(): string
    {
        ob_start();

        $stream = $this->streamCsv();
        fclose($stream);

        return ob_get_clean();
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

        return $stream;
    }

    /**
     * Resolve CSV header.
     *
     * @return array
     */
    protected function resolveCsvHeader(): array
    {
        $header = [];

        if (! $this->isEmpty()) {
            $single = $this->first();
            $header = array_keys(Arr::dot($single));
        }

        return $header;
    }
}
