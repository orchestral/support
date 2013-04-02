<?php namespace Orchestra\Support\Tests;

class BuilderTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Stub instance.
	 *
	 * @var Orchestra\Support\Builder
	 */
	protected $stub = null;

	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		$this->stub = new BuilderStub(function ($t) {});
	}

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		unset($this->stub);
	}
	
	/**
	 * Test Instance of Orchestra\Support\Builder.
	 *
	 * @test
	 * @group support
	 */	
	public function testInstanceOfBuilder()
	{
		$stub1 = new BuilderStub(function ($t) { });
		$stub2 = BuilderStub::make(function ($t) { });
		
		$refl = new \ReflectionObject($stub1);
		$name = $refl->getProperty('name');
		$grid = $refl->getProperty('grid');
		
		$name->setAccessible(true);
		$grid->setAccessible(true);

		$this->assertInstanceOf('\Orchestra\Support\Builder', $stub1);
		$this->assertInstanceOf('\Orchestra\Support\Builder', $stub2);
		$this->assertInstanceOf('\Illuminate\Support\Contracts\RenderableInterface', $stub1);
		$this->assertInstanceOf('\Illuminate\Support\Contracts\RenderableInterface', $stub2);

		$this->assertNull($name->getValue($stub1));
		$this->assertNull($stub1->name);
		$this->assertInstanceOf('\Orchestra\Support\Tests\BuilderGrid', 
			$grid->getValue($stub1));
		$this->assertInstanceOf('\Orchestra\Support\Tests\BuilderGrid', $stub1->grid);
		$this->assertNull($grid->getValue($stub1)->name);
		$this->assertNull($stub1->grid->name);
	}

	/**
	 * Test Orchestra\Support\Builder::extend() method.
	 *
	 * @test
	 * @support support
	 */
	public function testExtendMethod()
	{
		$stub = new BuilderStub(function ($t) {});
		
		$this->assertNull($stub->grid->name);

		$stub->extend(function ($g)
		{
			$g->name = 'foo';
		});

		$this->assertEquals('foo', $stub->grid->name);
	}

	/**
	 * Test Orchestra\Support\Builder::__get throws exception.
	 *
	 * @expectedException \InvalidArgumentException
	 * @group support
	 */
	public function testMagicMethodGetThrowsException()
	{
		$expected = $this->stub->expected;
	}
	
	/**
	 * test Orchestra\Support\Builder::render() method.
	 *
	 * @test
	 * @group support
	 */
	public function testRenderMethod()
	{
		$mock1 = new BuilderStub(function ($t) {});
		$mock2 = new BuilderStub(function ($t) {});

		ob_start();
		echo $mock1;
		$output = ob_get_contents();
		ob_end_clean();

		$this->assertEquals('foo', $output);
		$this->assertEquals('foo', $mock2->render());
	}
}

class BuilderGrid {

	public $name = null;

}

class BuilderStub extends \Orchestra\Support\Builder {

	public function __construct(\Closure $callback)
	{
		$this->grid = new BuilderGrid;
	}

	public function render()
	{
		return 'foo';
	}
}