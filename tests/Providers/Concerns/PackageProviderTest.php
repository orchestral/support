<?php

namespace Orchestra\Support\Tests\Providers\Concerns;

use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;
use Mockery as m;
use Orchestra\Support\Providers\Concerns\PackageProvider;
use Orchestra\Testbench\TestCase;

class PackageProviderTest extends TestCase
{
    /** @test */
    public function it_can_use_package()
    {
        $this->instance('config', $config = m::mock('Orchestra\Contracts\Config\PackageRepository, \ArrayAccess'));
        $this->instance('files', $files = m::mock('Illuminate\Filesystem\Filesystem'));
        $this->instance('translator', $translator = m::mock('Illuminate\Translation\Translator'));
        $this->instance('view', $view = m::mock('Illuminate\Contracts\View\Factory'));

        $path = '/var/www/vendor/foo/bar';

        $files->shouldReceive('isDirectory')->once()
                ->with("{$path}/config")->andReturn(true)
            ->shouldReceive('isDirectory')->once()
                ->with("{$path}/lang")->andReturn(true)
            ->shouldReceive('isDirectory')->once()
                ->with("{$path}/views")->andReturn(true)
            ->shouldReceive('isDirectory')->once()
                ->with('/var/www/resources/views/packages/foo/bar')->andReturn(true);

        $config->shouldReceive('package')->once()
                ->with('foo/bar', "{$path}/config", 'foo')->andReturnNull()
            ->shouldReceive('get')->once()
                ->with('view.paths', [])->andReturn(['/var/www/resources/views']);

        $translator->shouldReceive('addNamespace')->once()
            ->with('foo', "{$path}/lang")->andReturnNull();

        $view->shouldReceive('addNamespace')->once()
                ->with('foo', '/var/www/resources/views/packages/foo/bar')->andReturnNull()
            ->shouldReceive('addNamespace')->once()
                ->with('foo', "{$path}/views")->andReturnNull();

        $stub = new class($this->app) extends ServiceProvider {
            use PackageProvider;
        };

        $this->assertNull($stub->package('foo/bar', 'foo', $path));

        $this->app->make('translator');
    }

    /** @test */
    public function it_cant_use_package_when_laravel_installed()
    {
        $this->instance('config', m::mock('Illuminate\Contracts\Config\Repository'));

        $stub = new class($this->app) extends ServiceProvider {
            use PackageProvider;

            public function isLaravelConfig()
            {
                return $this->hasPackageRepository() === false;
            }
        };

        $this->assertTrue($stub->isLaravelConfig());
    }

    /** @test */
    public function it_can_use_package_when_orchestra_installed()
    {
        $this->instance('config', m::mock('Orchestra\Contracts\Config\PackageRepository'));

        $stub = new class($this->app) extends ServiceProvider {
            use PackageProvider;

            public function isOrchestraConfig()
            {
                return $this->hasPackageRepository() === true;
            }
        };

        $this->assertTrue($stub->isOrchestraConfig());
    }

    /** @test */
    public function it_can_boot_using_laravel()
    {
        $stub = new class($this->app) extends ServiceProvider {
            use PackageProvider;

            public function boot()
            {
                $this->bootUsingLaravel('foo');
            }
        };

        $this->assertNull($stub->boot());
    }
}
