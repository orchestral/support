<?php namespace Orchestra\Support\Providers\Traits;

use Illuminate\Routing\Router;

trait FilterProviderTrait
{
    /**
     * Register route filters.
     *
     * @param  \Illuminate\Routing\Router  $router
     *
     * @return void
     */
    protected function registerRouteFilters(Router $router)
    {
        foreach ((array) $this->before as $before) {
            $router->before($before);
        }

        foreach ((array) $this->after as $after) {
            $router->after($after);
        }

        foreach ((array) $this->filters as $name => $filter) {
            $router->filter($name, $filter);
        }
    }
}
