<?php namespace Orchestra\Support\Tests;

class MemoryTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		$appMock = \Mockery::mock('Application')
			->shouldReceive('instance')->andReturn(true);

		$configMock = \Mockery::mock('Config')
			->shouldReceive('get')
				->with('orchestra::memory.default_table')->andReturn('options')
			->shouldReceive('get')
				->with('orchestra::memory.default_model')->andReturn('options');

		\Illuminate\Support\Facades\Config::setFacadeApplication($appMock->getMock());
		\Illuminate\Support\Facades\Config::swap($configMock->getMock());

		\Illuminate\Support\Facades\DB::setFacadeApplication($appMock->getMock());
		
		$cacheMock = \Mockery::mock('Cache')
			->shouldReceive('get')->andReturn(array())
			->shouldReceive('forever')->andReturn(true);

		\Illuminate\Support\Facades\Cache::setFacadeApplication($appMock->getMock());
		\Illuminate\Support\Facades\Cache::swap($cacheMock->getMock());

		\Orchestra\Support\Memory::extend('stub', function($driver, $config) 
		{
			return new MemoryStub($driver, $config);
		});
	}

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		\Mockery::close();
	}

	/**
	 * Test that Orchestra\Support\Memory::make() return an instanceof Orchestra\Support\Memory.
	 * 
	 * @test
	 * @group support
	 */
	public function testMake()
	{
		$eloquentMock = \Mockery::mock('EloquentModelMock')
			->shouldReceive('all')->andReturn(array());
		$queryMock = \Mockery::mock('DB\Query')
			->shouldReceive('get')->andReturn(array());
		$dbMock = \Mockery::mock('DB')
			->shouldReceive('table')->andReturn($queryMock->getMock());
		\Illuminate\Support\Facades\DB::swap($dbMock->getMock());

		$this->assertInstanceOf('\Orchestra\Support\Memory\Runtime', 
			\Orchestra\Support\Memory::make('runtime')); 
		$this->assertInstanceOf('\Orchestra\Support\Memory\Cache', 
			\Orchestra\Support\Memory::make('cache')); 
		$this->assertInstanceOf('\Orchestra\Support\Memory\Eloquent', 
			\Orchestra\Support\Memory::make('eloquent', array('name' => $eloquentMock->getMock()))); 
		$this->assertInstanceOf('\Orchestra\Support\Memory\Fluent', 
			\Orchestra\Support\Memory::make('fluent')); 
	}

	/**
	 * Test that Orchestra\Support\Memory::make() return exception when given invalid driver
	 *
	 * @expectedException \Exception
	 * @group support
	 */
	public function testMakeExpectedException()
	{
		\Orchestra\Support\Memory::make('orm');
	}

	/**
	 * Test Orchestra\Support\Memory::extend() return valid Memory instance.
	 *
	 * @test
	 * @group support
	 */
	public function testStubMemory()
	{
		$stub = \Orchestra\Support\Memory::make('stub.mock');

		$this->assertInstanceOf('\Orchestra\Support\Tests\MemoryStub', $stub);

		$refl    = new \ReflectionObject($stub);
		$storage = $refl->getProperty('storage');
		$storage->setAccessible(true);

		$this->assertEquals('stub', $storage->getValue($stub));
	}

	/**
	 * Test Orchestra\Support\Memory::__construct() method.
	 *
	 * @expectedException \RuntimeException
	 * @group support
	 */
	public function testConstructMethod()
	{
		$stub = new \Orchestra\Support\Memory;
	}

	/**
	 * Test Orchestra\Support\Memory::shutdown() method.
	 *
	 * @test
	 * @group support
	 */
	public function testShutdownMethod()
	{
		$stub = \Orchestra\Support\Memory::make();
		$this->assertTrue($stub === \Orchestra\Support\Memory::make());

		\Orchestra\Support\Memory::shutdown();

		$this->assertFalse($stub === \Orchestra\Support\Memory::make());
	}
}

class MemoryStub extends \Orchestra\Support\Memory\Driver
{
	/**
	 * Storage name
	 * 
	 * @access  protected
	 * @var     string  
	 */
	protected $storage = 'stub';

	/**
	 * No initialize method for runtime
	 *
	 * @access  public
	 * @return  void
	 */
	public function initiate() {}

	/**
	 * No shutdown method for runtime
	 *
	 * @access  public
	 * @return  void
	 */
	public function shutdown() {}
}



class EloquentModelMock {

	public function all() {}

	public function where($key, $condition, $value) {}
}