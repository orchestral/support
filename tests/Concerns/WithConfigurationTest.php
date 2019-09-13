<?php

namespace Orchestra\Support\Tests\Concerns;

use Orchestra\Support\Concerns\WithConfiguration;
use PHPUnit\Framework\TestCase;

class WithConfigurationTest extends TestCase
{
    use WithConfiguration;

    /** @test */
    public function it_can_get_configuration()
    {
        $this->configurations = [
            'foo' => 'bar',
        ];

        $this->assertSame([
            'foo' => 'bar',
        ], $this->getConfiguration());
    }

    /** @test */
    public function it_can_set_configuration()
    {
        $this->assertSame([], $this->configurations);

        $this->setConfiguration($config = [
            'foo' => 'bar',
        ]);

        $this->assertSame($config, $this->configurations);
        $this->assertSame($config, $this->getConfiguration());
    }
}
