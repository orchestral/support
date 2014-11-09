<?php namespace Orchestra\Support\Providers;

abstract class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register the package's config component namespaces.
     *
     * @param  string  $package
     * @param  string  $namespace
     * @param  string  $path
     * @return void
     */
    public function addConfigComponent($package, $namespace, $path)
    {
        if ($this->app['files']->isDirectory($path)) {
            $this->app['config']->package($package, $path, $namespace);
        }
    }

    /**
     * Register the package's language component namespaces.
     *
     * @param  string  $package
     * @param  string  $namespace
     * @param  string  $path
     * @return void
     */
    public function addLanguageComponent($package, $namespace, $path)
    {
        if ($this->app['files']->isDirectory($path)) {
            $this->app['translator']->addNamespace($namespace, $path);
        }
    }

    /**
     * Register the package's view component namespaces.
     *
     * @param  string  $package
     * @param  string  $namespace
     * @param  string  $path
     * @return void
     */
    public function addViewComponent($package, $namespace, $path)
    {
        $appView = $this->getAppViewPath($package);

        if ($this->app['files']->isDirectory($appView)) {
            $this->app['view']->addNamespace($namespace, $appView);
        }

        // Finally we will register the view namespace so that we can access each of
        // the views available in this package. We use a standard convention when
        // registering the paths to every package's views and other components.

        if ($this->app['files']->isDirectory($path)) {
            $this->app['view']->addNamespace($namespace, $path);
        }
    }

    /**
     * Register the package's component namespaces.
     *
     * @param  string  $package
     * @param  string  $namespace
     * @param  string  $path
     * @return void
     */
    public function package($package, $namespace = null, $path = null)
    {
        $namespace = $this->getPackageNamespace($package, $namespace);

        // In this method we will register the configuration package for the package
        // so that the configuration options cleanly cascade into the application
        // folder to make the developers lives much easier in maintaining them.
        $path = $path ?: $this->guessPackagePath();

        $this->addConfigComponent($package, $namespace, $path.'/config');

        // Next, we will check for any "language" components. If language files exist
        // we will register them with this given package's namespace so that they
        // may be accessed using the translation facilities of the application.

        $this->addLanguageComponent($package, $namespace, $path.'/lang');

        // Next, we will see if the application view folder contains a folder for the
        // package and namespace. If it does, we'll give that folder precedence on
        // the loader list for the views so the package views can be overridden.

        $this->addViewComponent($package, $namespace, $path.'/views');
    }
}
