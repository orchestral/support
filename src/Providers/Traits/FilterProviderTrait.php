<?php namespace Orchestra\Support\Providers\Traits;

use Illuminate\Routing\Router;

trait FilterProviderTrait
{
    /**
     * The filters that should run before all requests.
     *
     * @var array
     */
    protected $before = [];

    /**
     * The filters that should run after all requests.
     *
     * @var array
     */
    protected $after = [];

    /**
     * All available route filters.
     *
     * @var array
     */
    protected $filters = [];

    /**
     * Register route filters.
     *
     * @param  \Illuminate\Routing\Router  $router
     *
     * @return void
     */
    protected function registerRouteFilters(Router $router)
    {
        foreach ($this->before as $before) {
            $router->before($before);
        }

        foreach ($this->after as $after) {
            $router->after($after);
        }

        foreach ($this->filters as $name => $filter) {
            $router->filter($name, $filter);
        }
    }
}
