<?php

namespace Orchestra\Support\TestCase\Traits;

use Orchestra\Support\Traits\Validation;

class ValidationTest extends \PHPUnit_Framework_TestCase
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
