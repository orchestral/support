<?php namespace Orchestra\Support\Tests;

use Mockery as m;

class ValidatorTest extends \PHPUnit_Framework_TestCase {
	
	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		$_SERVER['validator.onFoo'] = null;

		$app = m::mock('\Illuminate\Foundation\Application');
		$app->shouldReceive('instance')->andReturn(true);

		\Illuminate\Support\Facades\Event::setFacadeApplication($app);
		\Illuminate\Support\Facades\Validator::setFacadeApplication($app);
	}
	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		unset($_SERVER['validator.onFoo']);
		m::close();
	}

	/**
	 * Test Orchestra\Support\Validator
	 *
	 * @test
	 */
	public function testValidation()
	{
		$event     = m::mock('Event');
		$validator = m::mock('Validator');
		$rules     = array('email' => array('email', 'numeric'), 'name' => 'any');

		$event->shouldReceive('fire')->once()->with('foo.event', m::any())->andReturn(null);
		$validator->shouldReceive('make')->once()->with(array(), $rules)->andReturn('foo');

		\Illuminate\Support\Facades\Event::swap($event);
		\Illuminate\Support\Facades\Validator::swap($validator);
		
		$stub = new FooValidator;
		$stub->on('foo')->bind(array('eric' => 'eric'));
		
		$validation = $stub->with(array(), 'foo.event');

		$this->assertEquals('foobar', $_SERVER['validator.onFoo']);
	}
}

class FooValidator extends \Orchestra\Support\Validator {

	protected static $rules = array(
		'email' => array('email', 'num{eric}'),
		'name'  => 'any',
	);

	protected function onFoo()
	{
		$_SERVER['validator.onFoo'] = 'foobar';
	}
}
