<?php

namespace Orchestra\Support\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

abstract class ServiceProvider extends BaseServiceProvider
{
    use Concerns\PackageProvider;
}
