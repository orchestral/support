<?php

namespace Orchestra\Support\Providers\Concerns;

use Illuminate\Foundation\AliasLoader;

trait AliasesProvider
{
    /**
     * Register facades aliases.
     *
     * @return void
     */
    protected function registerFacadesAliases(): void
    {
        $loader = AliasLoader::getInstance();

        foreach ((array) $this->facades as $facade => $aliases) {
            foreach ((array) $aliases as $alias) {
                $loader->alias($alias, $facade);
            }
        }
    }

    /**
     * Register the class aliases in the container.
     *
     * @return void
     */
    protected function registerCoreContainerAliases(): void
    {
        foreach ((array) $this->aliases as $key => $aliases) {
            foreach ((array) $aliases as $alias) {
                $this->app->alias($key, $alias);
            }
        }
    }
}
