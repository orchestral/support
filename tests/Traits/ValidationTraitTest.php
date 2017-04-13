<?php

namespace Orchestra\Support\TestCase\Traits;

use PHPUnit\Framework\TestCase;
use Orchestra\Support\Traits\ValidationTrait;

class ValidationTraitTest extends TestCase
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
