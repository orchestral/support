---
title: Support Change Log
---

## Version 2.0 {#v2-0}

### v2.0.11 {#v2-0-11}

* Refactor and Simplify `Orchestra\Support\Nesty`.
* Implement [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) coding standard.

### v2.0.10 {#v2-0-10}

* `Illuminate\Support\Str::title()` is implemented, remove duplicate method.
* Add support to use `Orchestra\Support\Validator::extendScope()`, useful to have when need to deal with conditional rules <http://laravel.com/docs/validation#conditionally-adding-rules>.
* Refactor `Orchestra\Support\Str::streamGetContents()`.

### v2.0.9 {#v2-0-9}

* Add `Orchestra\Support\Nesty::is()` to return instance of `Illuminate\Support\Fluent` to allow further chaining of the instance.

### v2.0.8 {#v2-0-8}

* Add `Orchestra\Support\Str::searchable()` for better pattern matching.
* Add Guardfile.

### v2.0.7 {#v2-0-7}

* Add `Orchestra\Support\Messages::extend()` and tweak how Messages notification can be manipulated on current request.

### v2.0.6 {#v2-0-6}

* Code improvements.
* Add `Orchestra\Support\Ftp\RuntimeException::getParameters()`.

### v2.0.5 {#v2-0-5}

* Fixed an invalid called to `Orchestra\Support\Nesty::add_parent()`.
* Allow `Orchestra\Support\Nesty` to prepend an item without knowing the current first item.

### v2.0.4 {#v2-0-4}

* Refactor `Orchestra\Support\Validator` to minimize usage of `Illuminate\Support\Fluent`, this allow rules to be assigned as array and only pass as instance of Fluent during event (to allow pass by references).

### v2.0.3 {#v2-0-3}

* Allow `Orchestra\Support\Validator::on()` should accept additional parameters.
* Add `Orchestra\Support\Validator::setRules()` to override rules, and set it as an instanceof `Illuminate\Support\Fluent`.

### v2.0.2 {#v2-0-2}

* `Orchestra\Support\Manager` should be able to set blacklisted name, for example `Orchestra\Memory` shouldn't allow dotted.

### v2.0.1 {#v2-0-1}

* `Orchestra\Support\Validator::$rules` should utilize `Illuminate\Support\Fluent`.

### v2.0.0 {#v2-0-0}

* Migrate `Orchestra\Support` from Orchestra Platform 1.2.
* Split service provider to `Orchestra\Support\DecoratorServiceProvider` and `Orchestra\Support\MessagesServiceProvider`.
* `Orchestra\Support\Messages` now use `Session::put()` instead of `Session::flash()`.
* Add `Orchestra\Support\Validator` to manage validation using class.
* Add `Orchestra\Support\Nesty` from `Orchestra\Widget` so it can be reusable in any other component.
* Deprecate and remove `Orchestra\Messages::shutdown()` method, use `Orchestra\Messages::save()` instead.
* Move `Orchestra\Support\Decorator` to `Orchestra\View\Decorator`.
* Dreprecate and remove `Orchestra\Messages::add()` method as `Illuminate\Support\MessageBag` already allow chaining.
* Allow data bindings on `Orchestra\Support\Validator`.
* Add `Orchestra\Support\Str::humanize()` to convert slug to normal string.
* Remove all static properties from `Orchestra\Support\Validator`.
