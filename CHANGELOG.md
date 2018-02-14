# Changelog

This changelog references the relevant changes (bug and security fixes) done to `orchestra/support`.

## 3.5.2

Released: 2017-11-21

### Changes

* Simplify `Orchestra\Support\Transformer`.

## 3.5.1

Released: 2017-10-30

### Changes

* Allows `Orchestra\Support\Traits\QueryFilter` to access `Illuminate\Database\Query\Expression` to exclude expression with `.` to be converted to relationship `whereHas` query.

## 3.5.0

Released: 2017-09-03

### Changes

* Update support for Laravel Framework v5.5.

### Removed

* Remove deprecated `Orchestra\Support\Contracts\CsvableInterface`, replaced with `Orchestra\Support\Contracts\Csvable`.

## 3.4.3

Released: 2017-10-30

### Changes

* Allows `Orchestra\Support\Traits\QueryFilter` to access `Illuminate\Database\Query\Expression` to exclude expression with `.` to be converted to relationship `whereHas` query.

## 3.4.2

Released: 2017-07-11

### Changes

* Add support to require `danielstjules/stringy` `~3.0`.

## 3.4.1

Released: 2017-04-15

### Fixes

* Fixes `Orchestra\Support\Transformer` implementations.

## 3.4.0

Released: 2017-03-12

### Changes

* Update support for Laravel Framework v5.4.

### Removed

* Remove deprecated classes:
    - `Orchestra\Support\Providers\Traits\AliasesProviderTrait`
    - `Orchestra\Support\Providers\Traits\EventProviderTrait`
    - `Orchestra\Support\Providers\Traits\MiddlewareProviderTrait`
    - `Orchestra\Support\Providers\Traits\PackageProviderTrait`
    - `Orchestra\Support\Traits\DataContainerTrait`
    - `Orchestra\Support\Traits\DescendibleTrait`
    - `Orchestra\Support\Traits\MacroableTrait`
    - `Orchestra\Support\Traits\ObservableTrait`
    - `Orchestra\Support\Traits\QueryFilterTrait`
    - `Orchestra\Support\Traits\EloquentConnectionTrait`
    - `Orchestra\Support\Traits\UploadableTrait`
    - `Orchestra\Support\Traits\ValidationTrait`