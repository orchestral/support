<?php

namespace Orchestra\Support\TestCase;

use PHPUnit\Framework\TestCase;
use Orchestra\Support\Collection;

class CollectionTest extends TestCase
{
    /** @test */
    function it_can_be_converted_to_csv()
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
