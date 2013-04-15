<?php namespace Orchestra\Support;

use Illuminate\Support\ServiceProvider;

class SupportServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;
	
	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerMessages();
		$this->registerDecorator();
	}

	/**
	 * Register the service provider for Decorator.
	 *
	 * @return void
	 */
	protected function registerDecorator()
	{
		$this->app['orchestra.decorator'] = $this->app->share(function($app)
		{
			return new Decorator;
		});
	}

	/**
	 * Register the service provider for Messages.
	 *
	 * @return void
	 */
	protected function registerMessages()
	{
		$this->app['orchestra.messages'] = $this->app->share(function($app)
		{
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
		$this->registerMessagesEvents();
	}

	/**
	 * Register the events needed for messages.
	 *
	 * @return void
	 */
	protected function registerMessagesEvents()
	{
		$app = $this->app;

		$app->after(function($request, $response) use ($app)
		{
			$app->make('orchestra.messages')->shutdown();
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('orchestra.decorator', 'orchestra.messages');
	}
}