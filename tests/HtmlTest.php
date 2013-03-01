<?php namespace Orchestra\Support\Tests;

class HtmlTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Setup the test environment.
	 */
	public function setUp() 
	{
		$appMock = \Mockery::mock('Application')
			->shouldReceive('instance')->andReturn(true);

		$configMock = \Mockery::mock('Config')
			->shouldReceive('get')
				->with('application.encoding')->andReturn('UTF-8');

		\Illuminate\Support\Facades\Config::setFacadeApplication($appMock->getMock());
		\Illuminate\Support\Facades\Config::swap($configMock->getMock());
	}

	/**
	 * Teardown the test environment.
	 */
	public function tearDown() 
	{
		\Mockery::close();
	}
	/**
	 * Test Orchestra\Support\Html::create() with content
	 * 
	 * @test
	 * @group support
	 */
	public function testCreateWithContent()
	{
		$expected = '<div class="foo">Bar</div>';
		$output   = \Orchestra\Support\Html::create('div', 'Bar', array('class' => 'foo'));

		$this->assertEquals($expected, $output);
	}

	/**
	 * Test Orchestra\Support\Html::create() without content
	 * 
	 * @test
	 * @group support
	 */
	public function testCreateWithoutContent()
	{
		$expected = '<img src="hello.jpg" class="foo">';
		$output   = \Orchestra\Support\Html::create('img', array(
			'src' => 'hello.jpg', 
			'class' => 'foo',
		));

		$this->assertEquals($expected, $output);

		$expected = '<img src="hello.jpg" class="foo">';
		$output   = \Orchestra\Support\Html::create('img', null, array(
			'src' => 'hello.jpg', 
			'class' => 'foo',
		));

		$this->assertEquals($expected, $output);
	}

	/**
	 * Test Orchestra\Support\Html::entities() method
	 *
	 * @test
	 * @group support
	 */
	public function testEntitiesMethod()
	{
		$output = \Orchestra\Support\Html::raw('<img src="foo.jpg">');
		$this->assertEquals('<img src="foo.jpg">', 
			\Orchestra\Support\Html::entities($output));

		$output = '<img src="foo.jpg">';
		$this->assertEquals('&lt;img src=&quot;foo.jpg&quot;&gt;', 
			\Orchestra\Support\Html::entities($output));
	}

	/**
	 * Test Orchestra\Support\Html::decode() method.
	 *
	 * @test
	 * @group support
	 */
	public function testDecodeMethod()
	{
		$expected = '<img src="foo.jpg">';
		$this->assertEquals($expected,
			\Orchestra\Support\Html::decode('&lt;img src=&quot;foo.jpg&quot;&gt;'));
	}

	/**
	 * Test Orchestra\Support\Html::specialchars() method.
	 *
	 * @test
	 * @group support
	 */
	public function testSpecialCharsMethod()
	{
		$expected = '&lt;img src=&quot;foo.jpg&quot;&gt;';
		$this->assertEquals($expected,
			\Orchestra\Support\Html::specialChars('<img src="foo.jpg">'));
	}

	/**
	 * Test Orchestra\Support\Html::raw() method.
	 *
	 * @test
	 * @group support
	 */
	public function testRawExpressionMethod()
	{
		$output = \Orchestra\Support\Html::raw('hello');
		$this->assertInstanceOf('\Orchestra\Support\Expression', $output);
	}

	/**
	 * Test Orchestra\Support\Html::compileAttributes() method.
	 *
	 * @test
	 * @group support
	 */
	public function testCompileAttributesMethod()
	{
		$output   = \Orchestra\Support\Html::compileAttributes(array('class' => 'span4 table'), array('id' => 'foobar'));
		$expected = array('id' => 'foobar', 'class' => 'span4 table');
		$this->assertEquals($expected, $output);

		$output   = \Orchestra\Support\Html::compileAttributes(array('class' => 'span4 !span12'), array('class' => 'span12'));
		$expected = array('class' => 'span4');
		$this->assertEquals($expected, $output);

		$output   = \Orchestra\Support\Html::compileAttributes(array('id' => 'table'), array('id' => 'foobar', 'class' => 'span4'));
		$expected = array('id' => 'table', 'class' => 'span4');
		$this->assertEquals($expected, $output);
	}
}
