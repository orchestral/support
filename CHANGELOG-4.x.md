# Changelog for 4.x

This changelog references the relevant changes (bug and security fixes) done to `orchestra/support`.

## 4.0.0

### Added

* Added `Orchestra\Support\Concerns\WithConfiguration`.

### Changes

* Update support for Laravel Framework v6.0+.

### Removed

* Remove deprecated `Orchestra\Support\Morph`.
* Remove deprecated `Orchestra\Support\Providers\Traits\AliasesProvider`, use `Orchestra\Support\Providers\Concerns\AliasesProvider` instead.
* Remove deprecated `Orchestra\Support\Providers\Traits\EventProvider`, use `Orchestra\Support\Providers\Concerns\EventProvider` instead.
* Remove deprecated `Orchestra\Support\Providers\Traits\MiddlewareProvider`, use `Orchestra\Support\Providers\Concerns\MiddlewareProvider` instead.
* Remove deprecated `Orchestra\Support\Providers\Traits\PackageProvider`, use `Orchestra\Support\Providers\Concerns\PackageProvider` instead.
