<?php namespace Orchestra\Support\TestCase;

use Orchestra\Support\Collection;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test Orchestra\Support\Collection::toCsv() method.
     *
     * @test
     */
    public function testToCsvMethod()
    {
        $stub = new Collection(array(
            array('id' => 1, 'name' => 'Mior Muhammad Zaki'),
            array('id' => 2, 'name' => 'Taylor Otwell'),
        ));

        $expected = <<<EXPECTED
id,name
1,"Mior Muhammad Zaki"
2,"Taylor Otwell"

EXPECTED;

        $this->assertEquals($expected, $stub->toCsv());
    }
}
