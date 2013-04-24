Orchestra Platform Support Component
==============

Orchestra\Support is basically a basic set of class required by Orchestra Platform. The idea behind it is similar to what is Illuminate\Support to Laravel 4 Framework.
 
[![Build Status](https://travis-ci.org/orchestral/support.png?branch=master)](https://travis-ci.org/orchestral/support)

## Quick Installation

To install through composer, simply put the following in your `composer.json` file:

```json
{
	"require": {
		"orchestra/support": "2.0.0"
	},
}
```

Next add the service provider in `app/config/app.php`.

```php
'providers' => array(
	
	// ...
	
	'Orchestra\Support\DecoratorServiceProvider',
	'Orchestra\Support\MessagesServiceProvider',
),
```

You might want to add following facades to class aliases in `app/config/app.php`:

```php
'aliases' => array(

	// ...

	'Orchestra\Decorator' => 'Orchestra\Support\Facades\Decorator',
	'Orchestra\Messages' => 'Orchestra\Support\Facades\Messages',
),
```

## Resources

* [Documentation](http://docs.orchestraplatform.com/pages/components/support)
* [Change Logs](https://github.com/orchestral/support/wiki/Change-Logs)