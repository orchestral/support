---
title: Support Change Log

---

## Version 3.0 {#v3-0}

### v3.0.4 {#v3-0-4}

* Core:
  - Add `Orchestra\Support\Traits\DataContainerTrait::secureGet()` and `Orchestra\Support\Traits\DataContainerTrait::secureSet()`.

### v3.0.3 {#v3-0-3}

* Core:
  - Fixes a bug with `Orchestra\Support\Nesty::has()` returning `true` on not existent child attributes/item.
* Providers:
  - Add `Orchestra\Support\Providers\PipelineServiceProvider` which boot `Orchestra\Support\Providers\Traits\FilterProviderTrait` and `Orchestra\Support\Providers\Traits\MiddlewareProviderTrait`.

### v3.0.2 {#v3-0-2}

* Providers:
  - Add `Orchestra\Support\Providers\Traits\PackageProviderTrait::bootUsingLaravel()` helper.
  - Add `Orchestra\Support\Providers\Traits\PackageProviderTrait::hasPackageRepository()` helper.

### v3.0.1 {#v3-0-1}

* Providers:
  - Move reusable code from `Orchestra\Support\Providers\ServiceProvider` to `Orchestra\Support\Providers\Traits\PackageProviderTrait`.

### v3.0.0 {#v3-0-0}

* Update support to Laravel Framework v5.0.
* Split components to three (3) sub-components; Core, Facades, and Providers.
* Rename `Orchestra\Support\Ftp` to `Orchestra\Support\Ftp\Client`.
* Core:
  - Remove deprecated `Orchestra\Support\Messages` and `Orchestra\Support\MessagesServiceProvider`.
  - Add `Orchestra\Support\Nesty::has()` to check whether node exists.
  - Rename `Orchestra\Support\Nesty::getItems()` to `Orchestra\Support\Nesty::items()` for consistency.
  - Remove `Orchestra\Support\Relic`, duplicate of `Orchestra\Support\Traits\DataContainerTrait`.
  - Move `Orchestra\Support\Traits\ControllerResponseTrait` to `orchestra/routing` (subsplit of `orchestra/kernel`).
  - Directly inject validation factory and event dispatcher in `Orchestra\Support\Validator` instead of using Facades.
* Facades:
  - Add `Orchestra\Support\Facades\Avatar`.
  - Add `Orchestra\Support\Facades\HTML`.
  - Rename `Orchestra\Support\Facades\Acl` to `Orchestra\Support\Facades\ACL`.
  - Rename `Orchestra\Support\Facades\App` to `Orchestra\Support\Facades\Foundation`.
  - Rename `Orchestra\Support\Facades\Site` to `Orchestra\Support\Facades\Meta`.
* Providers:
  - Add `Orchestra\Support\Providers\ServiceProvider` which support registering packages.
  - Add `Orchestra\Support\Providers\FilterServiceProvider`.
  - Add `Orchestra\Support\Providers\Traits\AliasesProviderTrait`.

## Version 2.2 {#v2-2}

### v2.2.5 {#v2-2-5}

* Add ability to allow (`only`) or exclude (`except`) columns from filterable on `Orchestra\Support\Traits\QueryFilterTrait::setupBasicQueryFilter()`.

### v2.2.4 {#v2-2-4}

* Convert multi-dimensional array to single level array when using `Orchestra\Support\Str::replace()` method.
* Utilize `Illuminate\Support\Arr`.

### v2.2.3 {#v2-2-3}

* `Orchestra\Support\Traits\QueryFilterTrait::setupBasicQueryFilter()` should priotize `order_by` and `direction` key for consistency.
* Separate `Messages` class to it's own repository, accessible as `orchestra/messages`.

### v2.2.2 {#v2-2-2}

* `Orchestra\Support\Traits\QueryFilterTrait::setupBasicQueryFilter()` should allow filtering other than `*_at` fields.

### v2.2.1 {#v2-2-1}

* Fixes exception shouldn't be thrown when casting `Orchestra\Support\Collection` to CSV when no data is available.

### v2.2.0 {#v2-2-0}

* Rename `getSession` and `setSession` to `getSessionStore` and `setSessionStore` for consistency on `Orchestra\Support\Messages`.
* Add multiple traits:
  - `Orchestra\Support\Traits\ControllerResponseTrait` to add response helpers for controller.
  - `Orchestra\Support\Traits\MacroableTrait` for working with macro enabled classes.
  - `Orchestra\Support\Traits\QueryFilterTrait` to create basic filtering based on URL query string for query builder or eloquent.
  - `Orchestra\Support\Traits\UploadableTrait` for uploading files.

## Version 2.1 {#v2-1}

### v2.1.4 {#v2-1-4}

* Fixes exception shouldn't be thrown when casting `Orchestra\Support\Collection` to CSV when no data is available.

### v2.1.3 {#v2-1-2}

* Convert `Orchestra\Support\Nesty` to utilize `Orchestra\Support\Collection`.

### v2.1.2 {#v2-1-2}

* Add `Orchestra\Support\Collection` which bring support to native to CSV export.

### v2.1.1 {#v2-1-1}

* Allow to specify `$prefix` and `$suffix` for `Orchestra\Support\Str::replace()` helper.
* Implement [PSR-4](https://github.com/php-fig/fig-standards/blob/master/proposed/psr-4-autoloader/psr-4-autoloader.md) autoloading structure.

### v2.1.0 {#v2-1-0}

* `Orchestra\Support\Manager` should be able to set blacklisted name, for example `Orchestra\Memory` shouldn't allow dotted.
* Allow `Orchestra\Support\Nesty` to prepend an item without knowing the current first item.
* Add `Orchestra\Support\Messages::extend()` and tweak how Messages notification can be manipulated on current request.
* Add `Orchestra\Support\Nesty::is()` to return instance of `Illuminate\Support\Fluent` to allow further chaining of the instance.
* Add `Orchestra\Support\Str::searchable()` for better pattern matching.
* Add `Orchestra\Support\Relic`.
* Add `Orchestra\Support\Str::replace()`.
* `Illuminate\Support\Str::title()` is implemented, remove duplicate method.
* Add support to use `Orchestra\Support\Validator::extendScope()`, useful to have when need to deal with conditional rules <http://laravel.com/docs/validation#conditionally-adding-rules>.
* Refactor `Orchestra\Support\Str::streamGetContents()`.
* Implement [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) coding standard.
* Refactor `Orchestra\Support\Validator::getBindedRules()` to use `Orchestra\Support\Str::replace()`.
* Refactor `Orchestra\Support\Nesty::decendants()`.
* Return `Orchestra\Support\Nesty::getItems()` as instance of `Illuminate\Support\Collection`.
* Add support for custom messages on `Orchestra\Support\Validator` using `$phrases` protected property.
* Add following facades:
  - `Orchestra\Support\Facades\Debug`
  - `Orchestra\Support\Facades\Notifier`
  - `Orchestra\Support\Facades\Warden`

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
