<?php namespace Orchestra\Support\Tests;

class StrTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Test Orchestra\Support\Str::getFromStream() method.
	 *
	 * @test
	 * @group support
	 */
	public function testGetFromStreamMethod()
	{
		$base_path = __DIR__.'/stub/';
		$expected = 'a:2:{s:4:"name";s:9:"Orchestra";s:5:"theme";a:2:{s:7:"backend";s:7:"default";s:8:"frontend";s:7:"default";}}';
		$stream   = fopen($base_path.'driver1.stub.php', 'r');
		$output   = \Orchestra\Support\Str::getFromStream($stream);

		$this->assertEquals($expected, $output);

		$expected = array(
			'name'  => 'Orchestra',
			'theme' => array(
				'backend' => 'default',
				'frontend' => 'default',
			),
		);

		$this->assertEquals($expected, unserialize($output));

		$expected = 'a:2:{s:4:"name";s:9:"Orchestra";s:5:"theme";a:2:{s:7:"backend";s:7:"default";s:8:"frontend";s:7:"default";}}';
		$stream   = fopen($base_path.'driver2.stub.php', 'r');
		$output   = \Orchestra\Support\Str::getFromStream($stream);

		$this->assertEquals($expected, $output);

		$expected = array(
			'name'  => 'Orchestra',
			'theme' => array(
				'backend' => 'default',
				'frontend' => 'default',
			),
		);

		$this->assertEquals($expected, unserialize($output));
	}
}