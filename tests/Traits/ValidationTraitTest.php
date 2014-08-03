<?php namespace Orchestra\Support\Traits\TestCase;

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
        $this->assertEquals(array(), $this->getValidationEvents());
        $this->assertEquals(array(), $this->getValidationPhrases());
        $this->assertEquals(array(), $this->getValidationRules());
    }
}
