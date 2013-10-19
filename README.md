Orchestra Platform Support Component
==============

`Orchestra\Support` is basically a basic set of class required by Orchestra Platform. The idea behind it is similar to what is `Illuminate\Support` to Laravel 4 Framework.

[![Latest Stable Version](https://poser.pugx.org/orchestra/support/v/stable.png)](https://packagist.org/packages/orchestra/support) 
[![Total Downloads](https://poser.pugx.org/orchestra/support/downloads.png)](https://packagist.org/packages/orchestra/support) 
[![Build Status](https://travis-ci.org/orchestral/support.png?branch=master)](https://travis-ci.org/orchestral/support) 
[![Coverage Status](https://coveralls.io/repos/orchestral/support/badge.png?branch=master)](https://coveralls.io/r/orchestral/support?branch=master)

## Quick Installation

To install through composer, simply put the following in your `composer.json` file:

```json
{
	"require": {
		"orchestra/support": "2.0.*"
	}
}
```

Next add the service provider in `app/config/app.php`.

```php
'providers' => array(
	
	// ...
	
	'Orchestra\Support\MessagesServiceProvider',
),
```

## Resources

* [Documentation](http://orchestraplatform.com/docs/2.0/components/support)
* [Change Log](http://orchestraplatform.com/docs/2.0/components/support/changes#v2.1)
