<?php

namespace Orchestra\Support\Tests\Providers;

use Orchestra\Support\Providers\Concerns\PackageProvider;
use Orchestra\Support\Providers\ServiceProvider;
use PHPUnit\Framework\TestCase;

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
