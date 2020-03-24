# Changelog for 5.x

This changelog references the relevant changes (bug and security fixes) done to `orchestra/support`.

## 5.1.0

Released: 2020-03-24

### Changes

* Refactor `Orchestra\Support\Validator`:
    - Add `listen()` and `validate()` methods as replacement to `with()`.
    - Add `state()` method as replacement to `on()` method.

### Deprecated

* Deprecate `on()` and `with()` from `Orchestra\Support\Validator`.

## 5.0.0

Released: 2020-03-04

### Changes

* Update support for Laravel Framework v7+.
* Add `Orchestra\Support\Concerns\WithConfiguration::setConfigurationFrom()` method.

### Removed

* Remove deprecated 
    - `Orchestra\Support\Facades\Profiler`
    - `Orchestra\Support\Concerns\QueryFilter`
