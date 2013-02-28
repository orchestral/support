<?php namespace Orchestra\Support\Tests\Memory;

class FluentTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		$appMock = \Mockery::mock('Application')
			->shouldReceive('instance')->andReturn(true);

		\Illuminate\Support\Facades\DB::setFacadeApplication($appMock->getMock());
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
	public static function providerFluent()
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
		$queryMock = \Mockery::mock('DB\Query')
			->shouldReceive('get')->andReturn(static::providerFluent());
		$dbMock = \Mockery::mock('DB')
			->shouldReceive('table')->andReturn($queryMock->getMock());
		\Illuminate\Support\Facades\DB::swap($dbMock->getMock());
			
		$stub = new \Orchestra\Support\Memory\Fluent('stub', array(
			'table' => 'orchestra_options',
		));

		$this->assertInstanceOf('\Orchestra\Support\Memory\Fluent', $stub);
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
		$checkWithCountQueryMock = \Mockery::mock('DB\Query')
			->shouldReceive('count')->andReturn(1);
		$checkWithoutCountQueryMock = \Mockery::mock('DB\Query')
			->shouldReceive('count')->andReturn(0);

		$selectQueryMock = \Mockery::mock('DB\Query')
			->shouldReceive('update')
				->with(array('value' => serialize('foobar is wicked')))
				->once()->andReturn(true)
			->shouldReceive('insert')
				->once()->andReturn(true)
			->shouldReceive('where')
				->with('name', '=', 'foo')->andReturn($checkWithCountQueryMock->getMock())
			->shouldReceive('where')
				->with('name', '=', 'hello')->andReturn($checkWithCountQueryMock->getMock())
			->shouldReceive('where')
				->with('name', '=', 'stubbed')->andReturn($checkWithoutCountQueryMock->getMock())
			->shouldReceive('get')
				->andReturn(static::providerFluent());

		$selectQueryMock->shouldReceive('where')
			->with('id', '=', 1)->andReturn($selectQueryMock->getMock());

		$dbMock = \Mockery::mock('DB')
			->shouldReceive('table')->andReturn($selectQueryMock->getMock());
		\Illuminate\Support\Facades\DB::swap($dbMock->getMock());

		$stub = new \Orchestra\Support\Memory\Fluent('stub', array(
			'table' => 'orchestra_options',
		));

		$stub->put('foo', 'foobar is wicked');
		$stub->put('stubbed', 'Foobar was awesome');
		$stub->shutdown();
	}
}