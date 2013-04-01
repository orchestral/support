<?php namespace Orchestra\Support\Tests\Form;

class GridTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		$appMock = \Mockery::mock('Application')
			->shouldReceive('instance')->andReturn(true);

		$configMock = \Mockery::mock('Config')
			->shouldReceive('get')
				->with('orchestra::support.form.fieldset')->andReturn(array(
					'select'   => array(),
					'textarea' => array(),
					'input'    => array(),
					'password' => array(),
					'file'     => array(),
					'radio'    => array(),
				))
			->shouldReceive('get')
				->with('orchestra::support.form.templates', array())->andReturn(array(
					'input'    => function ($data) { return $data->name; },
					'textarea' => function ($data) { return $data->name; },
					'password' => function ($data) { return $data->name; },
					'file'     => function ($data) { return $data->name; },
					'radio'    => function ($data) { return $data->name; },
					'checkbox' => function ($data) { return $data->name; },
					'select'   => function ($data) { return $data->name; },
				));

		\Illuminate\Support\Facades\Config::setFacadeApplication($appMock->getMock());
		\Illuminate\Support\Facades\Config::swap($configMock->getMock());

		\Illuminate\Support\Facades\Form::setFacadeApplication($appMock->getMock());
	}

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		\Mockery::close();
	}

	/**
	 * Test instanceof Orchestra\Support\Form\Grid.
	 *
	 * @test
	 * @group support
	 */
	public function testInstanceOfGrid()
	{
		$stub = new \Orchestra\Support\Form\Grid(array(
			'submitButton' => 'Submit',
			'attributes'   => array('id' => 'foo'),
			'view'         => 'foo',
		));

		$stub->attributes = array('class' => 'foobar');

		$refl         = new \ReflectionObject($stub);
		$attributes   = $refl->getProperty('attributes');
		$submitButton = $refl->getProperty('submitButton');
		$view         = $refl->getProperty('view');

		$attributes->setAccessible(true);
		$submitButton->setAccessible(true);
		$view->setAccessible(true);

		$this->assertInstanceOf('\Orchestra\Support\Form\Grid', $stub);
		$this->assertEquals('Submit', $submitButton->getValue($stub));
		$this->assertEquals('foo', $view->getValue($stub));

		$this->assertEquals('foo', $stub->view());
		$this->assertEquals('foo', $stub->view);
		$this->assertEquals(array('id' => 'foo', 'class' => 'foobar'), $attributes->getValue($stub));
	}

	/**
	 * Test Orchestra\Support\Form\Grid::row() method.
	 *
	 * @test
	 * @group support
	 */
	public function testRowMethod()
	{
		$mock = new \Orchestra\Support\Fluent;
		$stub = new \Orchestra\Support\Form\Grid(array());
		$stub->row($mock);

		$refl = new \ReflectionObject($stub);
		$row  = $refl->getProperty('row');
		$row->setAccessible(true);

		$this->assertEquals($mock, $row->getValue($stub));
		$this->assertTrue(isset($stub->row));
	}

	/**
	 * Test Orchestra\Support\Form\Grid::layout() method.
	 *
	 * @test
	 * @group support
	 */
	public function testLayoutMethod()
	{
		$stub = new \Orchestra\Support\Form\Grid(array());

		$refl = new \ReflectionObject($stub);
		$view = $refl->getProperty('view');
		$view->setAccessible(true);

		$stub->layout('horizontal');
		$this->assertEquals('orchestra::support.form.horizontal', $view->getValue($stub));

		$stub->layout('vertical');
		$this->assertEquals('orchestra::support.form.vertical', $view->getValue($stub));

		$stub->layout('foo');
		$this->assertEquals('foo', $view->getValue($stub));
	}

	/**
	 * Test Orchestra\Support\Form\Grid::attributes() method.
	 *
	 * @test
	 * @group support
	 */
	public function testAttributesMethod()
	{
		$stub = new \Orchestra\Support\Form\Grid(array());

		$refl   = new \ReflectionObject($stub);
		$attributes = $refl->getProperty('attributes');
		$attributes->setAccessible(true);

		$stub->attributes(array('class' => 'foo'));

		$this->assertEquals(array('class' => 'foo'), $attributes->getValue($stub));
		$this->assertEquals(array('class' => 'foo'), $stub->attributes());

		$stub->attributes('id', 'foobar');

		$this->assertEquals(array('id' => 'foobar', 'class' => 'foo'), $attributes->getValue($stub));
		$this->assertEquals(array('id' => 'foobar', 'class' => 'foo'), $stub->attributes());
	}

	/**
	 * Test Orchestra\Support\Form\Grid::fieldset() method.
	 *
	 * @test
	 * @group support
	 */
	public function testFieldsetMethod()
	{
		$stub = new \Orchestra\Support\Form\Grid(array());

		$refl      = new \ReflectionObject($stub);
		$fieldsets = $refl->getProperty('fieldsets');
		$fieldsets->setAccessible(true);

		$this->assertEquals(array(), $fieldsets->getValue($stub));

		$stub->fieldset('Foobar', function ($f) {});
		$stub->fieldset(function ($f) {});

		$fieldset = $fieldsets->getValue($stub);

		$this->assertInstanceOf('\Orchestra\Support\Form\Fieldset', 
			$fieldset[0]);
		$this->assertEquals('Foobar', 
			$fieldset[0]->name);
		$this->assertInstanceOf('\Orchestra\Support\Form\Fieldset', 
			$fieldset[1]);
		$this->assertNull($fieldset[1]->name);
	}

	/**
	 * Test Orchestra\Support\Form\Grid::hidden() method.
	 *
	 * @test
	 * @group support
	 */
	public function testHiddenMethod()
	{
		$formMock = \Mockery::mock('Form')
			->shouldReceive('hidden')
				->once()
				->with('foo', 'foobar', \Mockery::any())
				->andReturn('hidden_foo')
			->shouldReceive('hidden')
				->once()
				->with('foobar', 'stubbed', \Mockery::any())
				->andReturn('hidden_foobar');

		\Illuminate\Support\Facades\Form::swap($formMock->getMock());

		$stub = new \Orchestra\Support\Form\Grid(array());
		$stub->row(new \Orchestra\Support\Fluent(array(
			'foo'    => 'foobar',
			'foobar' => 'foo',
		)));

		$stub->hidden('foo');
		$stub->hidden('foobar', function ($f)
		{
			$f->value('stubbed');
		});

		$refl    = new \ReflectionObject($stub);
		$hiddens = $refl->getProperty('hiddens'); 
		$hiddens->setAccessible(true);

		$data = $hiddens->getValue($stub);
		$this->assertEquals('hidden_foo', $data['foo']);
		$this->assertEquals('hidden_foobar', $data['foobar']);
	}

	/**
	 * Test Orchestra\Support\Form\Grid magic method __call() throws 
	 * exception.
	 *
	 * @expectedException \InvalidArgumentException
	 * @group support
	 */
	public function testMagicMethodCallThrowsException()
	{
		$stub = new \Orchestra\Support\Form\Grid(array());

		$stub->invalid_method();
	}

	/**
	 * Test Orchestra\Support\Form\Grid magic method __get() throws 
	 * exception.
	 *
	 * @expectedException \InvalidArgumentException
	 * @group support
	 */
	public function testMagicMethodGetThrowsException()
	{
		$stub = new \Orchestra\Support\Form\Grid(array());

		$invalid = $stub->invalid_property;
	}

	/**
	 * Test Orchestra\Support\Form\Grid magic method __set() throws 
	 * exception.
	 *
	 * @expectedException \InvalidArgumentException
	 * @group support
	 */
	public function testMagicMethodSetThrowsException()
	{
		$stub = new \Orchestra\Support\Form\Grid(array());

		$stub->invalid_property = array('foo');
	}

	/**
	 * Test Orchestra\Support\Form\Grid magic method __set() throws 
	 * exception when $values is not an array.
	 *
	 * @expectedException \InvalidArgumentException
	 * @group support
	 */
	public function testMagicMethodSetThrowsExceptionValuesNotAnArray()
	{
		$stub = new \Orchestra\Support\Form\Grid(array());

		$stub->attributes = 'foo';
	}

	/**
	 * Test Orchestra\Support\Form\Grid magic method __isset() throws 
	 * exception.
	 *
	 * @expectedException \InvalidArgumentException
	 * @group support
	 */
	public function testMagicMethodIssetThrowsException()
	{
		$stub = new \Orchestra\Support\Form\Grid(array());

		$invalid = isset($stub->invalid_property) ? true : false;
	}
}