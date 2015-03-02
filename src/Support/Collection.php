<?php namespace Orchestra\Support;

use Illuminate\Support\Arr;
use Orchestra\Support\Contracts\CsvableInterface;
use Illuminate\Support\Collection as BaseCollection;

class Collection extends BaseCollection implements CsvableInterface
{
    /**
     * {@inheritdoc}
     */
    public function toCsv()
    {
        $delimiter = ',';
        $enclosure = '"';
        $header    = $this->resolveCsvHeader();

        ob_start();

        $instance = fopen('php://output', 'r+');

        fputcsv($instance, $header, $delimiter, $enclosure);

        foreach ($this->items as $key => $item) {
            fputcsv($instance, Arr::dot($item), $delimiter, $enclosure);
        }

        return ob_get_clean();
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
