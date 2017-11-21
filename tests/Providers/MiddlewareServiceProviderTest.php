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

    /** @test */
    public function instance_has_proper_signature()
    {
        $stub = new StubMiddlewareProvider(null);

        $this->assertContains(MiddlewareProvider::class, class_uses_recursive(get_class($stub)));
    }

    /** @test */
    public function it_can_be_registered()
    {
        $stub = new StubMiddlewareProvider(null);

        $this->assertContains(MiddlewareProvider::class, class_uses_recursive(get_class($stub)));

        $this->assertNull($stub->register());
    }

    /** @test */
    public function it_can_be_booted()
    {
        $app = new Container();

        $router = m::mock('\Illuminate\Routing\Router');
        $kernel = m::mock('\Illuminate\Contracts\Http\Kernel');

        $router->shouldReceive('aliasMiddleware')->once()->with('foobar', 'FoobarMiddleware')->andReturnNull();
        $router->shouldReceive('middlewareGroup')->once()->with('api', ['ApiMiddleware'])->andReturnNull();
        $kernel->shouldReceive('pushMiddleware')->once()->with('FooMiddleware')->andReturnNull();

        $stub = new StubMiddlewareProvider($app);

        $this->assertNull($stub->boot($router, $kernel));
    }
}

class StubMiddlewareProvider extends MiddlewareServiceProvider
{
    protected $middleware = ['FooMiddleware'];
    protected $middlewareGroups = ['api' => ['ApiMiddleware']];
    protected $routeMiddleware = ['foobar' => 'FoobarMiddleware'];

    public function register()
    {
        //
    }
}
