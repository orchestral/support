<?php namespace Orchestra\Support\Providers\TestCase; 

use Mockery as m;
use Illuminate\Container\Container;
use Orchestra\Support\Providers\PipelineServiceProvider;

class PipelineServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Foundation\Providers\PipelineServiceProvider::register()
     * method.
     *
     * @test
     */
    public function testRegisterMethod()
    {
        $stub = new StubPipelineProvider(null);

        $this->assertNull($stub->register());
    }

    /**
     * Test Orchestra\Foundation\Providers\PipelineServiceProvider::boot()
     * method.
     *
     * @test
     */
    public function testBootMethod()
    {
        $app = new Container();

        $router = m::mock('\Illuminate\Routing\Router');
        $kernel = m::mock('\Illuminate\Contracts\Http\Kernel');

        $router->shouldReceive('before')->once()->with('BeforeFilter')->andReturnNull()
            ->shouldReceive('after')->once()->with('AfterFilter')->andReturnNull()
            ->shouldReceive('filter')->once()->with('foo', 'FooFilter')->andReturnNull()
            ->shouldReceive('middleware')->once()->with('foobar', 'FoobarMiddleware')->andReturnNull();

        $kernel->shouldReceive('pushMiddleware')->once()->with('FooMiddleware')->andReturnNull();

        $stub = new StubPipelineProvider($app);

        $this->assertNull($stub->boot($router, $kernel));
    }
}

class StubPipelineProvider extends PipelineServiceProvider
{
    protected $before = ['BeforeFilter'];
    protected $after  = ['AfterFilter'];
    protected $filters = ['foo' => 'FooFilter'];
    protected $middleware = ['FooMiddleware'];
    protected $routeMiddleware = ['foobar' => 'FoobarMiddleware'];
}
