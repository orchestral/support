<?php namespace Orchestra\Support\Providers\TestCase;

use Orchestra\Support\Providers\ServiceProvider;

class ServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test Orchestra\Support\Providers\PipelineServiceProvider method signature.
     *
     * @test
     */
    public function testInstanceSignature()
    {
        $stub = new StubBasicProvider(null);

        $this->assertContains('Orchestra\Support\Providers\Traits\PackageProvider', class_uses_recursive(get_class($stub)));
    }
}

class StubBasicProvider extends ServiceProvider
{
    public function register()
    {
        //
    }
}
