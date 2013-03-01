<?php namespace Orchestra\Support\Tests\Form;

class FieldsetTest extends \PHPUnit_Framework_TestCase {

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
					'radio'    => array(),
				))
			->shouldReceive('get')
				->with('orchestra::support.form.templates', array())->andReturn(array(
					'input'    => function ($data) { return $data->name; },
					'textarea' => function ($data) { return $data->name; },
					'password' => function ($data) { return $data->name; },
					'radio'    => function ($data) { return $data->name; },
					'checkbox' => function ($data) { return $data->name; },
					'select'   => function ($data) { return $data->name; },
				));

		\Illuminate\Support\Facades\Config::setFacadeApplication($appMock->getMock());
		\Illuminate\Support\Facades\Config::swap($configMock->getMock());

		$inputMock = \Mockery::mock('Input')
			->shouldReceive('old')->andReturn(array());

		\Illuminate\Support\Facades\Input::setFacadeApplication($appMock->getMock());
		\Illuminate\Support\Facades\Input::swap($inputMock->getMock());

	}

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		\Mockery::close();
	}

	/**
	 * Test instance of Orchestra\Support\Form\Fieldset.
	 *
	 * @test
	 * @group support
	 */
	public function testInstanceOfFieldset()
	{
		$stub = new \Orchestra\Support\Form\Fieldset('foo', function ($f)
		{
			$f->attributes(array('class' => 'foo'));
		});

		$this->assertInstanceOf('\Orchestra\Support\Form\Fieldset', $stub);
		$this->assertEquals(array(), $stub->controls);
		$this->assertTrue(isset($stub->name));

		$this->assertEquals(array('class' => 'foo'), $stub->attributes);
		$this->assertEquals('foo', $stub->name());

		$stub->attributes = array('class' => 'foobar');
		$this->assertEquals(array('class' => 'foobar'), $stub->attributes);

	}

	/**
	 * Test Orchestra\Support\Form\Fieldset::of() method.
	 *
	 * @test
	 * @group support
	 */
	public function testOfMethod()
	{
		$stub = new \Orchestra\Support\Form\Fieldset(function ($f)
		{
			$f->control('text', 'id', function ($c)
			{
				$c->value('Foobar');
			});
		});		

		$output = $stub->of('id');

		$this->assertEquals('Foobar', $output->value);
		$this->assertEquals('Id', $output->label);
		$this->assertEquals('id', $output->id);
	}

	/**
	 * Test Orchestra\Support\Form\Fieldset::control() method.
	 *
	 * @test
	 * @group support
	 */
	public function testControlMethod()
	{
		$stub = new \Orchestra\Support\Form\Fieldset(function ($f)
		{
			$f->control('text', 'text_foo', function ($c)
			{
				$c->label('Foo')->value('foobar');
			});

			$f->control('input:email', 'email_foo', function ($c)
			{
				$c->label('Foo')->value('foobar');
			});

			$f->control('password', 'password_foo', function ($c)
			{
				$c->label('Foo')->value('foobar');
			});

			$f->control('textarea', 'textarea_foo', function ($c)
			{
				$c->label('Foo')->value('foobar');
			});

			$f->control('radio', 'radio_foo', function ($c)
			{
				$c->label('Foo')->value('foobar')->checked(true);
			});

			$f->control('checkbox', 'checkbox_foo', function ($c)
			{
				$c->label('Foo')->value('foobar')->checked(true);
			});

			$f->control('select', 'select_foo', function ($c)
			{
				$c->label('Foo')->value('foobar')->options(array(
					'yes' => 'Yes',
					'no'  => 'No',
				));
			});

			$f->control('text', 'a', 'A');

			$f->control('text', function($c)
			{
				$c->name('b')->label('B')->value('value-of-B');
			});
		});

		$output = $stub->of('text_foo');

		$this->assertEquals('text_foo', $output->id);
		$this->assertEquals('text_foo', 
			 call_user_func($output->field, new \Illuminate\Support\Fluent, $output));

		$output = $stub->of('email_foo');

		$this->assertEquals('email_foo', $output->id);
		$this->assertEquals('email_foo', 
			 call_user_func($output->field, new \Illuminate\Support\Fluent, $output));

		$output = $stub->of('password_foo');
		
		$this->assertEquals('password_foo', $output->id);
		$this->assertEquals('password_foo', 
			 call_user_func($output->field, new \Illuminate\Support\Fluent, $output));

		$output = $stub->of('textarea_foo');

		$this->assertEquals('textarea_foo', $output->id);
		$this->assertEquals('textarea_foo', 
			 call_user_func($output->field, new \Illuminate\Support\Fluent, $output));

		$output = $stub->of('radio_foo');

		$this->assertEquals('radio_foo', $output->id);
		$this->assertEquals('radio_foo', 
			 call_user_func($output->field, new \Illuminate\Support\Fluent, $output));

		$output = $stub->of('checkbox_foo');

		$this->assertEquals('checkbox_foo', $output->id);
		$this->assertEquals('checkbox_foo', 
			 call_user_func($output->field, new \Illuminate\Support\Fluent, $output));

		$output = $stub->of('select_foo');

		$this->assertEquals('select_foo', $output->id);
		$this->assertEquals('select_foo', 
			 call_user_func($output->field, new \Illuminate\Support\Fluent, $output));

		$output = $stub->of('a');

		$this->assertEquals('a', $output->id);
		$this->assertEquals('a', 
			 call_user_func($output->field, new \Illuminate\Support\Fluent, $output));

		$controls = $stub->controls;
		$output   = end($controls);

		$this->assertEquals('b', 
			 call_user_func($output->field, new \Illuminate\Support\Fluent, $output));
	}

	/**
	 * Test Orchestra\Support\Form\Fieldset::of() method throws exception.
	 *
	 * @expectedException \InvalidArgumentException
	 * @group support
	 */
	public function testOfMethodThrowsException()
	{
		$stub = new \Orchestra\Support\Form\Fieldset(function ($f) {});

		$output = $stub->of('id');
	}

	/**
	 * Test Orchestra\Support\Form\Grid::attributes() method.
	 *
	 * @test
	 * @group support
	 */
	public function testAttributesMethod()
	{
		$stub = new \Orchestra\Support\Form\Fieldset(function () {});

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
	 * Test Orchestra\Support\Form\Fieldset magic method __call() throws 
	 * exception.
	 *
	 * @expectedException \InvalidArgumentException
	 * @group support
	 */
	public function testMagicMethodCallThrowsException()
	{
		$stub = new \Orchestra\Support\Form\Fieldset(function ($f) {});

		$stub->invalid_method();
	}

	/**
	 * Test Orchestra\Support\Form\Fieldset magic method __get() throws 
	 * exception.
	 *
	 * @expectedException \InvalidArgumentException
	 * @group support
	 */
	public function testMagicMethodGetThrowsException()
	{
		$stub = new \Orchestra\Support\Form\Fieldset(function ($f) {});

		$invalid = $stub->invalid_property;
	}

	/**
	 * Test Orchestra\Support\Form\Fieldset magic method __set() throws 
	 * exception.
	 *
	 * @expectedException \InvalidArgumentException
	 * @group support
	 */
	public function testMagicMethodSetThrowsException()
	{
		$stub = new \Orchestra\Support\Form\Fieldset(function ($f) {});

		$stub->invalid_property = array('foo');
	}

	/**
	 * Test Orchestra\Support\Form\Fieldset magic method __set() throws 
	 * exception when $values is not an array.
	 *
	 * @expectedException \InvalidArgumentException
	 * @group support
	 */
	public function testMagicMethodSetThrowsExceptionValuesNotAnArray()
	{
		$stub = new \Orchestra\Support\Form\Fieldset(function ($f) {});

		$stub->attributes = 'foo';
	}

	/**
	 * Test Orchestra\Support\Form\Fieldset magic method __isset() throws 
	 * exception.
	 *
	 * @expectedException \InvalidArgumentException
	 * @group support
	 */
	public function testMagicMethodIssetThrowsException()
	{
		$stub = new \Orchestra\Support\Form\Fieldset(function ($f) {});

		$invalid = isset($stub->invalid_property) ? true : false;
	}

	/**
	 * Test Orchestra\Support\Form\Fieldset::render() throws 
	 * exception.
	 *
	 * @expectedException \InvalidArgumentException
	 * @group support
	 */
	public function testRenderMethodThrowsException()
	{
		\Orchestra\Support\Form\Fieldset::render(
			array(),
			new \Illuminate\Support\Fluent(array('method' => 'foo'))
		);
	}
}