<?php

namespace Orchestra\Support;

use Illuminate\Support\Str as BaseStr;
use Stringy\Stringy;

class Str extends BaseStr
{
    /**
     * Based on maximum column name length.
     *
     * @var int
     */
    public const MAX_COLUMN_NAME_LENGTH = 64;

    /**
     * Column names are alphanumeric strings that can contain
     * underscores (`_`) but can't start with a number.
     *
     * @var string
     */
    private const VALID_COLUMN_NAME_REGEX = '/^(?![0-9])[A-Za-z0-9_-]*$/';

    /**
     * Convert slug type text to human readable text.
     *
     * @param  string  $text
     *
     * @return string
     */
    public static function humanize(string $text): string
    {
        $text = str_replace(['-', '_'], ' ', $text);

        return Stringy::create($text)->humanize()->titleize();
    }

    /**
     * Replace a text using strtr().
     *
     * @param  string|array  $text
     * @param  array   $replacements
     * @param  string  $prefix
     * @param  string  $suffix
     *
     * @return string|array
     */
    public static function translate(
        $text,
        array $replacements = [],
        string $prefix = '{',
        string $suffix = '}'
    ) {
        $replacements = static::prepareBinding($replacements, $prefix, $suffix);

        $filter = static function ($text) use ($replacements) {
            return strtr($text, $replacements);
        };

        if (\is_array($text)) {
            $text = array_map($filter, $text);
        } else {
            $text = $filter($text);
        }

        return $text;
    }

    /**
     * Convert basic string to searchable result.
     *
     * @param  string  $text
     * @param  string  $wildcard
     * @param  string  $replacement
     *
     * @return array
     */
    public static function searchable(string $text, string $wildcard = '*', string $replacement = '%'): array
    {
        if (! static::contains($text, $wildcard)) {
            return [
                "{$text}",
                "{$text}{$replacement}",
                "{$replacement}{$text}",
                "{$replacement}{$text}{$replacement}",
            ];
        }

        return [str_replace($wildcard, $replacement, $text)];
    }

    /**
     * Validate column name.
     *
     * @param  string|null  $column
     *
     * @return bool
     */
    public static function validateColumnName(?string $column): bool
    {
        if (empty($column) || \strlen($column) > self::MAX_COLUMN_NAME_LENGTH) {
            return false;
        }

        if (! preg_match(self::VALID_COLUMN_NAME_REGEX, $column)) {
            return false;
        }

        return true;
    }

    /**
     * Convert filter to string, this process is required to filter stream
     * data return from Postgres where blob type schema would actually use
     * BYTEA and convert the string to stream.
     *
     * @param  mixed  $data
     *
     * @return string
     */
    public static function streamGetContents($data): string
    {
        // check if it's actually a resource, we can directly convert
        // string without any issue.
        if (! \is_resource($data)) {
            return $data;
        }

        // Get the content from stream.
        $hex = stream_get_contents($data);

        // For some reason hex would always start with 'x' and if we
        // don't filter out this char, it would mess up hex to string
        // conversion.
        if (preg_match('/^x(.*)$/', $hex, $matches)) {
            $hex = $matches[1];
        }

        // Check if it's actually a hex string before trying to convert.
        if (! ctype_xdigit($hex)) {
            return $hex;
        }

        return static::fromHex($hex);
    }

    /**
     * Convert hex to string.
     *
     * @param  string  $hex
     *
     * @return string
     */
    protected static function fromHex(string $hex): string
    {
        $data = '';

        // Convert hex to string.
        for ($i = 0; $i < \strlen($hex) - 1; $i += 2) {
            $data .= \chr(hexdec($hex[$i].$hex[$i + 1]));
        }

        return $data;
    }

    /**
     * Prepare bindings for text replacement.
     *
     * @param  array   $replacements
     * @param  string  $prefix
     * @param  string  $suffix
     *
     * @return array
     */
    protected static function prepareBinding(
        array $replacements = [],
        string $prefix = '{',
        string $suffix = '}'
    ): array {
        $replacements = Arr::dot($replacements);
        $bindings = [];

        foreach ($replacements as $key => $value) {
            if (is_scalar($value)) {
                $bindings["{$prefix}{$key}{$suffix}"] = $value;
            }
        }

        return $bindings;
    }
}
