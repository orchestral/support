<?php namespace Orchestra\Support\Tests;

class TableTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Stub instance.
	 *
	 * @var Orchestra\Support\Table
	 */
	protected $stub = null;

	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		$appMock = \Mockery::mock('Application')
			->shouldReceive('instance')->andReturn(true);

		$configMock = \Mockery::mock('Config')
			->shouldReceive('get')
				->with('orchestra::support.table', array())->andReturn(array());

		\Illuminate\Support\Facades\Config::setFacadeApplication($appMock->getMock());
		\Illuminate\Support\Facades\Config::swap($configMock->getMock());

		$requestMock = \Mockery::mock('Request')
			->shouldReceive('query')->andReturn(array());

		\Illuminate\Support\Facades\Request::setFacadeApplication($appMock->getMock());
		\Illuminate\Support\Facades\Request::swap($requestMock->getMock());

		$langMock = \Mockery::mock('Lang')
			->shouldReceive('get')->andReturn(array());

		\Illuminate\Support\Facades\Lang::setFacadeApplication($appMock->getMock());
		\Illuminate\Support\Facades\Lang::swap($langMock->getMock());

		$viewMock = \Mockery::mock('View')
			->shouldReceive('make')->andReturn(\Mockery::self())
			->shouldReceive('with')->andReturn(\Mockery::self())
			->shouldReceive('render')->andReturn('mocked');

		\Illuminate\Support\Facades\View::setFacadeApplication($appMock->getMock());
		\Illuminate\Support\Facades\View::swap($viewMock->getMock());

		$this->stub = \Orchestra\Support\Table::of('stub', function ($t) {});
	}

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		unset($this->stub);
		\Orchestra\Support\Table::$names = array();

		\Mockery::close();
	}
	
	/**
	 * Test Instance of Orchestra\Support\Table.
	 *
	 * @test
	 * @group support
	 */	
	public function testInstanceOfTable()
	{
		$stub = new \Orchestra\Support\Table(function ($t) { });
		
		$refl = new \ReflectionObject($stub);
		$name = $refl->getProperty('name');
		$grid = $refl->getProperty('grid');
		
		$name->setAccessible(true);
		$grid->setAccessible(true);

		$this->assertInstanceOf('\Orchestra\Support\Table', $stub);
		$this->assertInstanceOf('\Orchestra\Support\Builder', $stub);
		$this->assertInstanceOf('\Illuminate\Support\Contracts\RenderableInterface', $stub);
		
		$this->assertNull($name->getValue($stub));
		$this->assertNull($stub->name);
		$this->assertInstanceOf('\Orchestra\Support\Table\Grid', $grid->getValue($stub));
		$this->assertInstanceOf('\Orchestra\Support\Table\Grid', $stub->grid);
	}

	/**
	 * Test Orchestra\Support\Table::of() method.
	 *
	 * @test
	 * @group support
	 */
	public function testTableOfMethod()
	{
		$this->assertEquals(\Orchestra\Support\Table::of('stub'), $this->stub);
		$this->assertEquals('stub', $this->stub->name);
	}
	
	/**
	 * test Orchestra\Support\Table::render() method.
	 *
	 * @test
	 * @group support
	 */
	public function testRenderMethod()
	{
		$mock_data = array(
			new \Illuminate\Support\Fluent(array('id' => 1, 'name' => 'Laravel')),
			new \Illuminate\Support\Fluent(array('id' => 2, 'name' => 'Illuminate')),
			new \Illuminate\Support\Fluent(array('id' => 3, 'name' => 'Symfony')),
		);

		$mock1 = new \Orchestra\Support\Table(function ($t) use ($mock_data)
		{
			$t->rows($mock_data);
			$t->attributes(array('class' => 'foo'));

			$t->column('id');
			$t->column(function ($c) 
			{
				$c->id = 'name';
				$c->label('Name');
				$c->value(function ($row)
				{
					return $row->name;
				});
			});
		});

		$mock2 = new \Orchestra\Support\Table(function ($t) use ($mock_data)
		{
			$t->rows($mock_data);
			$t->attributes = array('class' => 'foo');

			$t->column('ID', 'id');
			$t->column('name', function ($c)
			{
				$c->value(function ($row)
				{
					return '<strong>'.$row->name.'</strong>';
				});
			});
		});

		ob_start();
		echo $mock1;
		$output = ob_get_contents();
		ob_end_clean();

		$this->assertEquals('mocked', $output);
		$this->assertEquals('mocked', $mock2->render());
	}
}
