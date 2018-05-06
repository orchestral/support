<?php

namespace Orchestra\Support\TestCase;

use Orchestra\Support\Fluent;
use PHPUnit\Framework\TestCase;

class FluentTest extends TestCase
{
    /** @test */
    public function it_can_be_transformed()
    {
        $stub = (new Fluent(['name' => 'Mior Muhammad Zaki', 'email' => 'crynobone@gmail.com']))
                    ->transform(function ($user) {
                        return [
                            'description' => "{$user->name} ({$user->email})",
                        ];
                    });

        $this->assertSame('Mior Muhammad Zaki (crynobone@gmail.com)', $stub->description);
    }
}
