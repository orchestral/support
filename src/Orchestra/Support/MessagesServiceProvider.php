<?php namespace Orchestra\Support;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class MessagesServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bindShared('orchestra.messages', function () {
            return new Messages;
        });
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $app = $this->app;

        $app->after(function () use ($app) {
            $app['orchestra.messages']->save();
        });
    }
}
