<?php

namespace Orchestra\Support\Tests;

use Orchestra\Support\Collection;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\Environment\OperatingSystem;

class CollectionTest extends TestCase
{
    /** @test */
    public function it_can_be_converted_to_csv()
    {
        $stub = new Collection([
            ['id' => 1, 'name' => 'Mior Muhammad Zaki'],
            ['id' => 2, 'name' => 'Taylor Otwell'],
        ]);

        $expected = "id,name\n1,\"Mior Muhammad Zaki\"\n2,\"Taylor Otwell\"\n";

        $this->assertEquals($expected, $stub->toCsv());
    }

    /** @test */
    public function it_can_be_stream_as_csv()
    {
        $stub = new Collection([
            ['id' => 1, 'name' => 'Mior Muhammad Zaki'],
            ['id' => 2, 'name' => 'Taylor Otwell'],
        ]);

        $expected = "id,name\n1,\"Mior Muhammad Zaki\"\n2,\"Taylor Otwell\"\n";

        ob_start();
        $stub->streamCsv();

        $this->assertEquals($expected, ob_get_clean());
    }
}
