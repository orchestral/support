<?php

namespace Orchestra\Support\TestCase\Traits;

use Orchestra\Support\Traits\ValidationTrait;

class ValidationTraitTest extends \PHPUnit_Framework_TestCase
{
    use ValidationTrait;

    /**
     * Test Orchestra\Support\Traits\ValidationTrait.
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
