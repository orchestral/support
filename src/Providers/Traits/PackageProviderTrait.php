<?php namespace Orchestra\Support\Providers\Traits;

use ReflectionClass;
use Orchestra\Contracts\Config\PackageRepository;

trait PackageProviderTrait
{
    /**
     * Register the package's config component namespaces.
     *
     * @param  string  $package
     * @param  string  $namespace
     * @param  string  $path
     *
     * @return void
     */
    public function addConfigComponent($package, $namespace, $path)
    {
        if ($this->hasPackageRepository()) {
            $this->app->make('config')->package($package, $path, $namespace);
        }
    }

    /**
     * Register the package's language component namespaces.
     *
     * @param  string  $package
     * @param  string  $namespace
     * @param  string  $path
     *
     * @return void
     */
    public function addLanguageComponent($package, $namespace, $path)
    {
        $this->app->make('translator')->addNamespace($namespace, $path);
    }

    /**
     * Register the package's view component namespaces.
     *
     * @param  string  $package
     * @param  string  $namespace
     * @param  string  $path
     *
     * @return void
     */
    public function addViewComponent($package, $namespace, $path)
    {
        $files = $this->app->make('files');
        $view  = $this->app->make('view');

        foreach ($this->getAppViewPaths($package) as $appView) {
            if ($files->isDirectory($appView)) {
                $view->addNamespace($namespace, $appView);
            }
        }

        // Finally we will register the view namespace so that we can access each of
        // the views available in this package. We use a standard convention when
        // registering the paths to every package's views and other components.

        $view->addNamespace($namespace, $path);
    }

    /**
     * Register the package's component namespaces.
     *
     * @param  string  $package
     * @param  string  $namespace
     * @param  string  $path
     *
     * @return void
     */
    public function package($package, $namespace = null, $path = null)
    {
        $namespace = $this->getPackageNamespace($package, $namespace);
        $files     = $this->app->make('files');

        // In this method we will register the configuration package for the package
        // so that the configuration options cleanly cascade into the application
        // folder to make the developers lives much easier in maintaining them.
        $path = $path ?: $this->guessPackagePath();

        if ($files->isDirectory($config = $path.'/config')) {
            $this->addConfigComponent($package, $namespace, $config);
        }

        // Next, we will check for any "language" components. If language files exist
        // we will register them with this given package's namespace so that they
        // may be accessed using the translation facilities of the application.

        if ($files->isDirectory($lang = $path.'/lang')) {
            $this->addLanguageComponent($package, $namespace, $lang);
        }

        // Next, we will see if the application view folder contains a folder for the
        // package and namespace. If it does, we'll give that folder precedence on
        // the loader list for the views so the package views can be overridden.

        if ($files->isDirectory($views = $path.'/views')) {
            $this->addViewComponent($package, $namespace, $views);
        }
    }

    /**
     * Guess the package path for the provider.
     *
     * @return string
     */
    public function guessPackagePath()
    {
        $path = (new ReflectionClass($this))->getFileName();

        return realpath(dirname($path).'/../../');
    }

    /**
     * Determine the namespace for a package.
     *
     * @param  string  $package
     * @param  string  $namespace
     *
     * @return string
     */
    protected function getPackageNamespace($package, $namespace)
    {
        if (is_null($namespace)) {
            list(, $namespace) = explode('/', $package);
        }

        return $namespace;
    }

    /**
     * Get the application package view paths.
     *
     * @param  string  $package
     *
     * @return array
     */
    protected function getAppViewPaths($package)
    {
        return array_map(function ($path) use ($package) {
            return "{$path}/packages/{$package}";
        }, $this->app->make('config')->get('view.paths', []));
    }

    /**
     * Has package repository available.
     *
     * @return bool
     */
    protected function hasPackageRepository()
    {
        return ($this->app->make('config') instanceof PackageRepository);
    }

    /**
     * Boot under Laravel setup.
     *
     * @param  string  $path
     *
     * @return void
     */
    protected function bootUsingLaravel($path)
    {
        //
    }
}
