<?php namespace Orchestra\Support\Providers\TestCase;

use Mockery as m;
use Illuminate\Container\Container;
use Orchestra\Support\Providers\FilterServiceProvider;

class FilterServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Foundation\Providers\FilterServiceProvider::register()
     * method.
     *
     * @test
     */
    public function testRegisterMethod()
    {
        $stub = new StubFilterProvider(null);

        $this->assertNull($stub->register());
    }

    /**
     * Test Orchestra\Foundation\Providers\FilterServiceProvider::boot()
     * method.
     *
     * @test
     */
    public function testBootMethod()
    {
        $app = new Container();

        $router = m::mock('\Illuminate\Routing\Router');

        $router->shouldReceive('before')->once()->with('BeforeFilter')->andReturnNull()
            ->shouldReceive('after')->once()->with('AfterFilter')->andReturnNull()
            ->shouldReceive('filter')->once()->with('foo', 'FooFilter')->andReturnNull();

        $stub = new StubFilterProvider($app);

        $this->assertNull($stub->boot($router));
    }
}

class StubFilterProvider extends FilterServiceProvider
{
    protected $before = ['BeforeFilter'];
    protected $after  = ['AfterFilter'];
    protected $filters = ['foo' => 'FooFilter'];
}
