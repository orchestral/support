<?php namespace Orchestra\Support\TestCase;

use Mockery as m;
use Orchestra\Support\MessagesServiceProvider;

class MessagesServiceProviderTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		m::close();
	}

	/**
	 * Test Orchestra\Support\MessagesServiceProvider::register() method.
	 *
	 * @test
	 */
	public function testRegisterMethod()
	{
		$app = m::mock('\Illuminate\Container\Container');
		$session = m::mock('\Illuminate\Session\Store');

		$app->shouldReceive('bindShared')->once()->with('orchestra.messages', m::type('Closure'))
				->andReturnUsing(function ($n, $c) use ($app) {
					$c($app);
				})
			->shouldReceive('offsetGet')->once()->with('session.store')->andReturn($session);

		$stub = new MessagesServiceProvider($app);
		$this->assertNull($stub->register());
	}

	/**
	 * Test Orchestra\Support\MessagesServiceProvider::boot() method.
	 *
	 * @test
	 */
	public function testBootMethod()
	{
		$app = m::mock('\Illuminate\Container\Container');
		$messages = m::mock('\Orchestra\Support\Messages');

		$app->shouldReceive('after')->once()->with(m::type('Closure'))
				->andReturnUsing(function ($c) {
					$c();
				})
			->shouldReceive('offsetGet')->once()->with('orchestra.messages')->andReturn($messages);
		$messages->shouldReceive('save')->once();

		$stub = new MessagesServiceProvider($app);
		$this->assertNull($stub->boot());
	}
}
