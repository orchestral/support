# Changelog for 4.x

This changelog references the relevant changes (bug and security fixes) done to `orchestra/support`.

## 4.1.0

Released: 2020-01-24

### Changes

* Override `ServiceProvider::loadFactoriesFrom()` to only be loaded from unit testing environment.
* Update `addLanguageComponent` and `addViewComponent` under `Orchestra\Support\Provider\Concerns\PackageProvider` based on `callAfterResolving` changes to `Illuminate\Support\ServiceProvider`.

### Fixes

* Fixes class name not shown when `Orchestra\Support\Transformer` can't transform the instance.

### Deprecated

* Deprecate `Orchestra\Support\Concerns\QueryFilter`, please use [laravie/query-filter](https://github.com/laravie/query-filter) instead.

## 4.0.2

Released: 2019-10-30

### Fixes

* Fixes `Orchestra\Support\Facades\Mail` accessor.

## 4.0.1

Released: 2019-09-14

### Changes

* Replace `danielstjules/stringy` with `statamic/stringy`.

## 4.0.0

Released: 2019-09-01

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
