Support Component
==============

* [Installation](#installation)
* [Configuration](#configuration)

`Orchestra\Support` is basically a basic set of class required by Orchestra Platform. The idea behind it is similar to what is `Illuminate\Support` to Laravel 4 Framework.

## Installation {#installation}

To install through composer, simply put the following in your `composer.json` file:

	{
		"require": {
			"orchestra/support": "2.1.*@dev"
		}
	}

## Configuration {#configuration}

Next add the service provider in `app/config/app.php`.

	'providers' => array(

		// ...

		'Orchestra\Support\MessagesServiceProvider',
	),
