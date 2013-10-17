Using Messages
==============

`Orchestra\Messages` utilize `Illuminate\Support\MessageBag` to bring notification support through-out Orchestra Platform (and Laravel 4).

## Adding a Message

Adding a message is as easy as following:

```php
Orchestra\Messages::add('success', 'A successful message');
```

You can also chain messages:

```php
Orchestra\Messages::add('success', 'A successful message')
	->add('error', 'Some error');
```
</article>

## Extending a Message to Current Response

There might be situation where you need to extend a message to the current response instead of the following request. You can do this with:

```php
Orchestra\Messages::extend(function ($message)
{
	$message->add('info', 'Read-only mode');
});
```

## Displaying the Message in a View

Here's an example how you can display the message:

```php
<?php 

$message = Orchestra\Messages::retrieve();

if ($message instanceof Orchestra\Support\Messages)
{
	foreach (['error', 'info', 'success'] as $key)
	{
		if ($message->has($key))
		{			
			$message->setFormat(
				'<div class="alert alert-'.$key.'">:message</div>'
			);
			echo implode('', $message->get($key));
		}
	}
}
```
