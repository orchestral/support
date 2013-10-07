<?php namespace Orchestra\Support\Tests;

use Mockery as m;

class ValidatorTest extends \PHPUnit_Framework_TestCase {
	
	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		$_SERVER['validator.onFoo'] = null;
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
		unset($_SERVER['validator.extendFoo']);
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
		$rules     = array('email' => array('email', 'foo:2'), 'name' => 'any');

		$event->shouldReceive('fire')->once()->with('foo.event', m::any())->andReturn(null);
		$validator->shouldReceive('make')->once()->with(array(), $rules)->andReturn($validator);

		\Illuminate\Support\Facades\Event::swap($event);
		\Illuminate\Support\Facades\Validator::swap($validator);
		
		$stub = new FooValidator;
		$stub->on('foo', array('orchestra'))->bind(array('id' => '2'));
		
		$validation = $stub->with(array(), 'foo.event');

		$this->assertEquals('orchestra', $_SERVER['validator.onFoo']);
		$this->assertInstanceOf('Validator', $_SERVER['validator.extendFoo']);
		$this->assertInstanceOf('Validator', $validation);
	}
}

class FooValidator extends \Orchestra\Support\Validator {

	protected $rules = array(
		'email' => array('email', 'foo:{id}'),
		'name'  => 'any',
	);

	protected function onFoo($value)
	{
		$_SERVER['validator.onFoo'] = $value;
	}

	protected function extendFoo($validation)
	{
		$_SERVER['validator.extendFoo'] = $validation;
	}
}
