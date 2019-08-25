<?php

namespace Orchestra\Support\Tests\Concerns;

use PHPUnit\Framework\TestCase;
use Orchestra\Support\Concerns\Validation;

class ValidationTest extends TestCase
{
    /** @test */
    public function it_can_access_validation_helpers()
    {
        $stub = new class() {
            use Validation;
        };

        $this->assertEquals([], $stub->getValidationEvents());
        $this->assertEquals([], $stub->getValidationPhrases());
        $this->assertEquals([], $stub->getValidationRules());
    }
}
