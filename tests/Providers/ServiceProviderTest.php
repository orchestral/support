<?php

namespace Orchestra\Support\TestCase\Providers;

use PHPUnit\Framework\TestCase;
use Orchestra\Support\Providers\ServiceProvider;
use Orchestra\Support\Providers\Concerns\PackageProvider;

class ServiceProviderTest extends TestCase
{
    /** @test */
    public function instance_has_proper_signature()
    {
        $stub = new class(null) extends ServiceProvider {
            public function register()
            {
                //
            }
        };

        $this->assertContains(PackageProvider::class, class_uses_recursive(get_class($stub)));
    }
}
