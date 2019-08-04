# Changelog

This changelog references the relevant changes (bug and security fixes) done to `orchestra/support`.

## 3.8.6

Released: 2019-08-04

### Changes

* Implements `Illuminate\Contracts\Support\DeferrableProvider` on `Orchestra\Support\Providers\CommandServiceProvider`.
* Use `static function` rather than `function` whenever possible, the PHP engine does not need to instantiate and later GC a `$this` variable for said closure.

## 3.8.5

Released: 2019-07-24

### Fixes

* Fixed `Orchestra\Support\Str::validateColumnName()` to return `false` when given `null` or empty string.
* Fixes `$timezone` parameter usage when using `Orchestra\Support\Timezone`.

## 3.8.4

Released: 2019-07-14

### Added

* Added `Orchestra\Support\Str::validateColumnName()`.

## 3.8.3

Released: 2019-06-29

### Added

* Added `Orchestra\Support\Timezone::whereHourInUtc()` helper method as replacement to `Timezone::on()`.

## 3.8.2

Released: 2019-06-28

### Added

* Added `Orchestra\Support\Timezone`.

## 3.8.1

Released: 2019-04-09

### Added

* Added `Orchestra\Support\Providers\Concerns\DiscoverableEventProvider`.

### Changes

* Update `Orchestra\Support\Providers\EventServiceProvider` to use `DiscoverableEventProvider`.

## 3.8.0

Released: 2019-12-27

### Changes

* Update support for Laravel Framework v5.8.
* Update upport for `danielstjules/stringy` to `^3.0`.

### Removed

* Remove deprecated traits:
    - `Orchestra\Support\Traits\DataContainer`
    - `Orchestra\Support\Traits\Descendible`
    - `Orchestra\Support\Traits\Marcoable`
    - `Orchestra\Support\Traits\Observable`
    - `Orchestra\Support\Traits\QueryFilter`
    - `Orchestra\Support\Traits\Testing\MockEloquentConnection`
    - `Orchestra\Support\Traits\Uploadable`
    - `Orchestra\Support\Traits\Validation`

## 3.7.1

Released: 2019-12-19

### Changes

* Improve performance by prefixing all global functions calls with `\` to skip the look up and resolve process and go straight to the global function.
* Use `Illuminate\Events\Dispatcher::dispatch()` instead deprecated `Illuminate\Events\Dispatcher::fire()`.
* Use `static::class` instead of `get_called_class()`.

## 3.7.0

Released: 2018-11-08

### Changes

* Update support for Laravel Framework v5.7.

## 3.6.2

Released: 2018-07-10

### Fixes

* Fixes `Orchestra\Support\Concerns\QueryFilter::buildWildcardForField()` to accept `Illuminate\Database\Query\Expression` as `$field` variable instead of casting it to `string`.

## 3.6.1

Released: 2018-07-07

### Added

* Add `Orchestra\Support\Serializer::getKey()`.

### Changes

* Moves `Orchestra\Support\Traits` to `Orchestra\Support\Concerns`.
* Moves `Orchestra\Support\Providers\Traits` to `Orchestra\Support\Providers\Concerns`.

## 3.6.0

Released: 2018-05-06

### Changes

* Update support for Laravel Framework v5.6.
