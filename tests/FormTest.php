<?php namespace Orchestra\Support\Tests;

class FormTest extends \PHPUnit_Framework_TestCase {

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
				->with('orchestra::support.form', \Mockery::any())->andReturn(array(
					'fieldset' => array(
						'select'   => array(),
						'textarea' => array(),
						'input'    => array(),
						'password' => array(),
						'file'     => array(),
						'radio'    => array(),
					),
				));

		\Illuminate\Support\Facades\Config::setFacadeApplication($appMock->getMock());
		\Illuminate\Support\Facades\Config::swap($configMock->getMock());

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

		$this->stub = \Orchestra\Support\Form::of('stub', function ($t) {});
	}

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		unset($this->stub);
		\Orchestra\Support\Form::$names = array();

		\Mockery::close();
	}
	
	/**
	 * Test Instance of Orchestra\Support\Form.
	 *
	 * @test
	 * @group support
	 */	
	public function testInstanceOfForm()
	{
		$stub = new \Orchestra\Support\Form(function ($t) { });
		
		$refl = new \ReflectionObject($stub);
		$name = $refl->getProperty('name');
		$grid = $refl->getProperty('grid');
		
		$name->setAccessible(true);
		$grid->setAccessible(true);

		$this->assertInstanceOf('\Orchestra\Support\Form', $stub);
		
		$this->assertNull($name->getValue($stub));
		$this->assertNull($stub->name);
		$this->assertInstanceOf('\Orchestra\Support\Form\Grid', $grid->getValue($stub));
		$this->assertInstanceOf('\Orchestra\Support\Form\Grid', $stub->grid);
	}

	/**
	 * test Orchestra\Support\Form::render()
	 *
	 * @test
	 * @group support
	 */
	public function testRenderMethod()
	{
		$mock_data = new \Orchestra\Support\Fluent(array(
			'id' => 1, 
			'name' => 'Laravel'
		));

		$mock1 = new \Orchestra\Support\Form(function ($form) use ($mock_data)
		{
			$form->row($mock_data);
			$form->attributes(array(
				'method' => 'POST',
				'action' => 'http://localhost',
				'class'  => 'foo',
			));
		});

		$mock2 = new \Orchestra\Support\Form(function ($form) use ($mock_data)
		{
			$form->row($mock_data);
			$form->attributes = array(
				'method' => 'POST',
				'action' => 'http://localhost',
				'class'  => 'foo'
			);
		});

		ob_start();
		echo $mock1;
		$output = ob_get_contents();
		ob_end_clean();

		$this->assertEquals('mocked', $output);
		$this->assertEquals('mocked', $mock2->render());
	}
}