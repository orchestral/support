<?php

namespace Orchestra\Support\TestCase\Traits;

use PHPUnit\Framework\TestCase;
use Orchestra\Support\Traits\Validation;

class ValidationTest extends TestCase
{
    use Validation;

    /**
     * Test Orchestra\Support\Traits\Validation.
     *
     * @test
     */
    public function testGetValidationHelpers()
    {
        $this->assertEquals([], $this->getValidationEvents());
        $this->assertEquals([], $this->getValidationPhrases());
        $this->assertEquals([], $this->getValidationRules());
    }
}
