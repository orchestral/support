<?php

namespace Orchestra\Support\TestCase\Traits;

use PHPUnit\Framework\TestCase;
use Orchestra\Support\Traits\Validation;

class ValidationTest extends TestCase
{
    use Validation;

    /** @test */
    public function get_validation_helpers()
    {
        $this->assertEquals([], $this->getValidationEvents());
        $this->assertEquals([], $this->getValidationPhrases());
        $this->assertEquals([], $this->getValidationRules());
    }
}
