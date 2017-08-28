<?php

namespace Orchestra\Support\TestCase\Providers;

use PHPUnit\Framework\TestCase;
use Orchestra\Support\Providers\ServiceProvider;
use Orchestra\Support\Providers\Traits\PackageProvider;

class ServiceProviderTest extends TestCase
{
    /**
     * Test Orchestra\Support\Providers\PipelineServiceProvider method signature.
     *
     * @test
     */
    public function testInstanceSignature()
    {
        $stub = new StubBasicProvider(null);

        $this->assertContains(PackageProvider::class, class_uses_recursive(get_class($stub)));
    }
}

class StubBasicProvider extends ServiceProvider
{
    public function register()
    {
        //
    }
}
