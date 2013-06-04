<?php namespace Orchestra\Support;

use Illuminate\Support\Str as S;

class Str extends S {

	/**
	 * Convert slug type text to human readable text.
	 *
	 * @static
	 * @access public
	 * @param  string   $text
	 * @return string
	 */
	public static function humanize($text)
	{
		return static::title(str_replace(array('-', '_'), ' ', $text));
	}

	/**
	 * Convert filter to string, this process is required to filter stream 
	 * data return from Postgres where blob type schema would actually use 
	 * BYTEA and convert the string to stream.
	 *
	 * @static
	 * @access public
	 * @param  mixed    $data
	 * @return string
	 */
	public static function streamGetContents($data)
	{
		// check if it's actually a resource, we can directly convert 
		// string without any issue.
		if (is_resource($data))
		{
			// Get the content from stream.
			$hex = stream_get_contents($data);

			// For some reason hex would always start with 'x' and if we
			// don't filter out this char, it would mess up hex to string 
			// conversion.
			if (preg_match('/^x(.*)$/', $hex, $matches)) $hex = $matches[1];

			// Check if it's actually a hex string before trying to convert.
			if (ctype_xdigit($hex))
			{
				$data = '';

				// Convert hex to string.
				for ($i = 0; $i < strlen($hex) - 1; $i += 2)
				{
					$data .= chr(hexdec($hex[$i].$hex[$i+1]));
				}
			}
			else 
			{
				$data = $hex;
			}
		}

		return $data;
	}

	/**
	 * Convert a string to title case (ucwords equivalent).
	 *
	 * <code>
	 *		// Convert a string to title case
	 *		$title = Str::title('taylor otwell');
	 *
	 *		// Convert a multi-byte string to title case
	 *		$title = Str::title('νωθρού κυνός');
	 * </code>
	 *
	 * @param  string  $value
	 * @return string
	 */
	public static function title($value)
	{
		return mb_convert_case($value, MB_CASE_TITLE, 'UTF-8');
	}
}
