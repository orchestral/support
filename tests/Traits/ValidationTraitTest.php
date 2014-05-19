<?php namespace Orchestra\Support\Traits\TestCase;

use Orchestra\Support\Traits\ValidationTrait;

class ValidationTraitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test Orchestra\Support\Traits\ValidationTrait.
     *
     * @test
     */
    public function testGetValidationHelpers()
    {
        $stub = new ValidationStub;

        $this->assertEquals(array(), $stub->getValidationEvents());
        $this->assertEquals(array(), $stub->getValidationPhrases());
        $this->assertEquals(array(), $stub->getValidationRules());
    }
}

class ValidationStub
{
    use ValidationTrait;
}
