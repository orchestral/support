<?php namespace Orchestra\Support;

use Orchestra\Support\Contracts\CsvableInterface;

class Collection extends \Illuminate\Support\Collection implements CsvableInterface
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
            fputcsv($instance, array_dot($item), $delimiter, $enclosure);
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
        $header = array();

        if (! $this->isEmpty()) {
            $single = $this->first();
            $header = array_keys(array_dot($single));
        }

        return $header;
    }
}
