---
title: Using Messages
---

`Orchestra\Messages` utilize `Illuminate\Support\MessageBag` to bring notification support through-out Orchestra Platform (and Laravel 4).

* [Adding a Message](#add-message)
* [Extending a Message to Current Response](#extend-for-current-request)
* [Displaying the Message in a View](#displaying-message)

## Adding a Message {#add-message}

Adding a message is as easy as following:

	Orchestra\Messages::add('success', 'A successful message');

You can also chain messages:

	Orchestra\Messages::add('success', 'A successful message')
		->add('error', 'Some error');

## Extending a Message to Current Response {#extend-for-current-request}

There might be situation where you need to extend a message to the current response instead of the following request. You can do this with:

	Orchestra\Messages::extend(function ($message) {
		$message->add('info', 'Read-only mode');
	});

## Displaying the Message in a View {#displaying-message}

Here's an example how you can display the message:

<?php

	$message = Orchestra\Messages::retrieve();

	if ($message instanceof Orchestra\Support\Messages) {
		foreach (['error', 'info', 'success'] as $key) {
			if ($message->has($key)) {
				$message->setFormat(
					'<div class="alert alert-'.$key.'">:message</div>'
				);
				echo implode('', $message->get($key));
			}
		}
	}
