<?php

namespace Orchestra\Support\TestCase;

use PHPUnit\Framework\TestCase;
use Orchestra\Support\Collection;

class CollectionTest extends TestCase
{
    /**
     * Test Orchestra\Support\Collection::toCsv() method.
     *
     * @test
     */
    public function testToCsvMethod()
    {
        $stub = new Collection([
            ['id' => 1, 'name' => 'Mior Muhammad Zaki'],
            ['id' => 2, 'name' => 'Taylor Otwell'],
        ]);

        $expected = <<<EXPECTED
id,name
1,"Mior Muhammad Zaki"
2,"Taylor Otwell"

EXPECTED;

        $this->assertEquals($expected, $stub->toCsv());
    }
}
