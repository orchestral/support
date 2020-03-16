<?php

namespace Orchestra\Support\Tests;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Mockery as m;
use Orchestra\Testbench\TestCase;

class ValidatorTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $_SERVER['validator.onFoo'] = null;
        $_SERVER['validator.onFoo'] = null;
    }

    /**
     * Teardown the test environment.
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($_SERVER['validator.onFoo']);
        unset($_SERVER['validator.extendFoo']);
        m::close();
    }

    /** @test */
    public function it_can_validate()
    {

        $rules = ['email' => ['email', 'foo:2'], 'name' => 'any'];
        $phrases = ['email.required' => 'Email required'];

        Event::shouldReceive('dispatch')->once()->with('foo.event', m::any())->andReturn(null);
        Validator::shouldReceive('make')->once()->with([], $rules, $phrases)
            ->andReturn(m::mock('Illuminate\Contracts\Validation\Validator'));

        $stub = new FooValidator();
        $stub->on('foo', ['orchestra'])->bind(['id' => '2']);

        $validation = $stub->with([], 'foo.event');

        $this->assertEquals('orchestra', $_SERVER['validator.onFoo']);
        $this->assertEquals($validation, $_SERVER['validator.extendFoo']);
        $this->assertInstanceOf('Illuminate\Contracts\Validation\Validator', $validation);
    }

    /** @test */
    public function it_can_validate_without_scope()
    {
        $rules = ['email' => ['email', 'foo:2'], 'name' => 'any'];
        $phrases = ['email.required' => 'Email required', 'name' => 'Any name'];

        Validator::shouldReceive('make')->once()->with([], $rules, $phrases)
            ->andReturn(m::mock('Illuminate\Contracts\Validation\Validator'));

        $stub = new FooValidator();
        $stub->bind(['id' => '2']);

        $validation = $stub->with([], null, ['name' => 'Any name']);

        $this->assertInstanceOf('Illuminate\Contracts\Validation\Validator', $validation);
    }
}

class FooValidator extends \Orchestra\Support\Validator
{
    protected $rules = [
        'email' => ['email', 'foo:{id}'],
        'name' => 'any',
    ];

    protected $phrases = [
        'email.required' => 'Email required',
    ];

    protected function onFoo($value)
    {
        $_SERVER['validator.onFoo'] = $value;
    }

    protected function extendFoo($validation)
    {
        $_SERVER['validator.extendFoo'] = $validation;
    }
}
