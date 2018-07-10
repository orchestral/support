# Changelog

This changelog references the relevant changes (bug and security fixes) done to `orchestra/support`.

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

## 3.5.4

Released: 2018-04-25

### Changes

* Allow to stream conversion to CSV when using `Orchestra\Support\Collection::toCsv()`.
* Ensure that `Illuminate\Contracts\Pagination\Paginator` can still be transform and serialize with pagination data intact.

## 3.5.3

Released: 2018-03-27

### Added

* Added `Orchestra\Support\Collection::streamCsv()` method.

### Fixes

* Fixes retrieving `$default` from `Orchestra\Support\Traits\DataContainer::get()` method.
* Fixes fetching `Orchestra\Support\Traits\DataContainer::allWithRemoved()` method.

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
