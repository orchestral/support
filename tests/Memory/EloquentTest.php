<?php namespace Orchestra\Support\Tests\Memory;

class EloquentTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		
	}

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		\Mockery::close();
	}

	/**
	 * Add data provider
	 * 
	 * @return array
	 */
	public static function providerEloquent()
	{
		return array(
			new \Illuminate\Support\Fluent(array('id' => 1, 'name' => 'foo', 'value' => 's:6:"foobar";')),
			new \Illuminate\Support\Fluent(array('id' => 2, 'name' => 'hello', 'value' => 's:5:"world";')),
		);
	}

	/**
	 * Test Orchestra\Support\Memory\Fluent::initiate() method.
	 *
	 * @test
	 * @group support
	 */
	public function testInitiateMethod()
	{
		$mock = \Mockery::mock('EloquentModelMock')
			->shouldReceive('all')->andReturn(static::providerEloquent());

		$stub = new \Orchestra\Support\Memory\Eloquent('stub', array(
			'name' => $mock->getMock(),
		));

		$this->assertInstanceOf('\Orchestra\Support\Memory\Eloquent', $stub);
		$this->assertEquals('foobar', $stub->get('foo'));
		$this->assertEquals('world', $stub->get('hello'));
	}

	/**
	 * Test Orchestra\Support\Memory\Fluent::shutdown() method.
	 *
	 * @test
	 * @group support
	 */
	public function testShutdownMethod()
	{
		$fooEntityMock = \Mockery::mock('FooEntityMock')
			->shouldReceive('fill')->once()->andReturn(true)
			->shouldReceive('save')->once()->andReturn(true);

		$checkWithCountQueryMock = \Mockery::mock('DB\Query')
			->shouldReceive('count')->andReturn(1)
			->shouldReceive('first')->andReturn($fooEntityMock->getMock());
		$checkWithoutCountQueryMock = \Mockery::mock('DB\Query')
			->shouldReceive('count')->andReturn(0);

		$mock = \Mockery::mock('EloquentModelMock')
			->shouldReceive('all')
				->andReturn(static::providerEloquent())
			->shouldReceive('create')
				->once()->andReturn(true)
			->shouldReceive('where')
				->with('name', '=', 'foo')->andReturn($checkWithCountQueryMock->getMock())
			->shouldReceive('where')
				->with('name', '=', 'hello')->andReturn($checkWithCountQueryMock->getMock())
			->shouldReceive('where')
				->with('name', '=', 'stubbed')->andReturn($checkWithoutCountQueryMock->getMock());

		$stub = new \Orchestra\Support\Memory\Eloquent('stub', array(
			'name' => $mock->getMock(),
		));

		$stub->put('foo', 'foobar is wicked');
		$stub->put('stubbed', 'Foobar was awesome');
		

		$stub->shutdown();
	}
}

class EloquentModelMock {

	public function all() {}

	public function where($key, $condition, $value) {}
}