<?php

namespace Orchestra\Support\TestCase\Providers;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Illuminate\Container\Container;
use Orchestra\Support\Providers\MiddlewareServiceProvider;
use Orchestra\Support\Providers\Traits\MiddlewareProvider;

class MiddlewareServiceProviderTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    protected function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Support\Providers\PipelineServiceProvider method signature.
     *
     * @test
     */
    public function testInstanceSignature()
    {
        $stub = new StubMiddlewareProvider(null);

        $this->assertContains(MiddlewareProvider::class, class_uses_recursive(get_class($stub)));
    }

    /**
     * Test Orchestra\Support\Providers\PipelineServiceProvider::register()
     * method.
     *
     * @test
     */
    public function testRegisterMethod()
    {
        $stub = new StubMiddlewareProvider(null);

        $this->assertContains('Orchestra\Support\Providers\Traits\MiddlewareProvider', class_uses_recursive(get_class($stub)));

        $this->assertNull($stub->register());
    }

    /**
     * Test Orchestra\Support\Providers\PipelineServiceProvider::boot()
     * method.
     *
     * @test
     */
    public function testBootMethod()
    {
        $app = new Container();

        $router = m::mock('\Illuminate\Routing\Router');
        $kernel = m::mock('\Illuminate\Contracts\Http\Kernel');

        $router->shouldReceive('aliasMiddleware')->once()->with('foobar', 'FoobarMiddleware')->andReturnNull();

        $kernel->shouldReceive('pushMiddleware')->once()->with('FooMiddleware')->andReturnNull();

        $stub = new StubMiddlewareProvider($app);

        $this->assertNull($stub->boot($router, $kernel));
    }
}

class StubMiddlewareProvider extends MiddlewareServiceProvider
{
    protected $middleware = ['FooMiddleware'];
    protected $routeMiddleware = ['foobar' => 'FoobarMiddleware'];

    public function register()
    {
        //
    }
}
