<?php

namespace Orchestra\Support\TestCase\Traits;

use PHPUnit\Framework\TestCase;
use Orchestra\Support\Traits\Validation;

class ValidationTest extends TestCase
{
    use Validation;

    /** @test */
    function it_can_access_validation_helpers()
    {
        $this->assertEquals([], $this->getValidationEvents());
        $this->assertEquals([], $this->getValidationPhrases());
        $this->assertEquals([], $this->getValidationRules());
    }
}
