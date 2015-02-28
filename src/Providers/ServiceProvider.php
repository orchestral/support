<?php namespace Orchestra\Support\Providers;

use Orchestra\Support\Providers\Traits\PackageProviderTrait;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

abstract class ServiceProvider extends BaseServiceProvider
{
    use PackageProviderTrait;
}
