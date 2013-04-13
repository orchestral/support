<?php namespace Orchestra\Support;

use Illuminate\Support\ServiceProvider;

class SupportServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['orchestra.messages'] = $this->app->share(function($app)
		{
			return new Messages;
		});
	}
}