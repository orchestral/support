<?php

namespace Orchestra\Support\TestCase;

use Mockery as m;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        $_SERVER['validator.onFoo'] = null;
        $_SERVER['validator.onFoo'] = null;
    }
    /**
     * Teardown the test environment.
     */
    protected function tearDown()
    {
        unset($_SERVER['validator.onFoo']);
        unset($_SERVER['validator.extendFoo']);
        m::close();
    }

    /** @test */
    function it_can_validate()
    {
        $event = m::mock('\Illuminate\Contracts\Events\Dispatcher');
        $validator = m::mock('\Illuminate\Contracts\Validation\Factory');

        $rules = ['email' => ['email', 'foo:2'], 'name' => 'any'];
        $phrases = ['email.required' => 'Email required'];

        $event->shouldReceive('fire')->once()->with('foo.event', m::any())->andReturn(null);
        $validator->shouldReceive('make')->once()->with([], $rules, $phrases)
            ->andReturn(m::mock('\Illuminate\Contracts\Validation\Validator'));

        $stub = new FooValidator($validator, $event);
        $stub->on('foo', ['orchestra'])->bind(['id' => '2']);

        $validation = $stub->with([], 'foo.event');

        $this->assertEquals('orchestra', $_SERVER['validator.onFoo']);
        $this->assertEquals($validation, $_SERVER['validator.extendFoo']);
        $this->assertInstanceOf('\Illuminate\Contracts\Validation\Validator', $validation);
    }

    /** @test */
    function it_can_validate_without_scope()
    {
        $event = m::mock('\Illuminate\Contracts\Events\Dispatcher');
        $validator = m::mock('\Illuminate\Contracts\Validation\Factory');

        $rules = ['email' => ['email', 'foo:2'], 'name' => 'any'];
        $phrases = ['email.required' => 'Email required', 'name' => 'Any name'];

        $validator->shouldReceive('make')->once()->with([], $rules, $phrases)
            ->andReturn(m::mock('\Illuminate\Contracts\Validation\Validator'));

        $stub = new FooValidator($validator, $event);
        $stub->bind(['id' => '2']);

        $validation = $stub->with([], null, ['name' => 'Any name']);

        $this->assertInstanceOf('\Illuminate\Contracts\Validation\Validator', $validation);
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
