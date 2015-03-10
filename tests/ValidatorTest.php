<?php namespace Orchestra\Support\TestCase;

use Mockery as m;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $_SERVER['validator.onFoo'] = null;
        $_SERVER['validator.onFoo'] = null;
    }
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        unset($_SERVER['validator.onFoo']);
        unset($_SERVER['validator.extendFoo']);
        m::close();
    }

    /**
     * Test Orchestra\Support\Validator.
     *
     * @test
     */
    public function testValidation()
    {
        $event     = m::mock('\Illuminate\Contracts\Events\Dispatcher');
        $validator = m::mock('\Illuminate\Contracts\Validation\Factory');

        $rules   = ['email' => ['email', 'foo:2'], 'name' => 'any'];
        $phrases = ['email.required' => 'Email required'];

        $event->shouldReceive('fire')->once()->with('foo.event', m::any())->andReturn(null);
        $validator->shouldReceive('make')->once()->with([], $rules, $phrases)
            ->andReturn(m::mock('\Illuminate\Validation\Validator'));

        $stub = new FooValidator($validator, $event);
        $stub->on('foo', ['orchestra'])->bind(['id' => '2']);

        $validation = $stub->with([], 'foo.event');

        $this->assertEquals('orchestra', $_SERVER['validator.onFoo']);
        $this->assertEquals($validation, $_SERVER['validator.extendFoo']);
        $this->assertInstanceOf('\Illuminate\Validation\Validator', $validation);
    }

    /**
     * Test Orchestra\Support\Validator without any scope.
     *
     * @test
     */
    public function testValidationWithoutAScope()
    {
        $event     = m::mock('\Illuminate\Contracts\Events\Dispatcher');
        $validator = m::mock('\Illuminate\Contracts\Validation\Factory');

        $rules   = ['email' => ['email', 'foo:2'], 'name' => 'any'];
        $phrases = ['email.required' => 'Email required', 'name' => 'Any name'];

        $validator->shouldReceive('make')->once()->with([], $rules, $phrases)
            ->andReturn(m::mock('\Illuminate\Validation\Validator'));

        $stub = new FooValidator($validator, $event);
        $stub->bind(['id' => '2']);

        $validation = $stub->with([], null, ['name' => 'Any name']);

        $this->assertInstanceOf('\Illuminate\Validation\Validator', $validation);
    }
}

class FooValidator extends \Orchestra\Support\Validator
{
    protected $rules = [
        'email' => ['email', 'foo:{id}'],
        'name'  => 'any',
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
