---
title: String Helper Class
---

`Orchestra\Support\Str` is probably one of the few internal API class that you might use directly. It extends `Illuminate\Support\Str` and offer some improvement.

* [Title](#str-title)
* [Stream Get Contents](#stream-get-contents)

## Title {#str-title}

Allow a string to be transformed to a proper title.

	use Orchestra\Support\Str;

	return Str::title('hello-world'); // would return "Hello World"

## Stream Get Contents {#stream-get-contents}

Unliked other database driver, when using blob with PostgreSQL, the return value from database is a stream instead of string, using above helper method help convert it properly back to string.

	use Orchestra\Support\Str;

	$str = Str::streamGetContents($blob);


> The method would return original string when it detect that it isn't a stream.
