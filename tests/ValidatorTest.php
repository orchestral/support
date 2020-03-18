<?php

namespace Orchestra\Support\Tests;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Mockery as m;
use Orchestra\Testbench\TestCase;

class ValidatorTest extends TestCase
{
    /** @test */
    public function it_can_validate()
    {
        $input = ['email' => 'crynobone@gmail.com'];
        $phrases = ['email.required' => 'Email required'];

        Event::shouldReceive('dispatch')->once()->with('foo.event', m::any())->andReturnNull();

        $stub = new FooValidator();
        $stub->state('optional-name', ['orchestra'])->bind(['id' => '2']);

        $validation = $stub->listen('foo.event')->validate($input);

        $this->assertInstanceOf('Illuminate\Contracts\Validation\Validator', $validation);
        $this->assertTrue($validation->passes());
    }

    /** @test */
    public function it_can_validate_without_scope()
    {
        $input = ['email' => 'crynobone@gmail.com', 'name' => 'Mior Muhammad Zaki'];
        $phrases = ['email.required' => 'Email required'];

        $stub = new FooValidator();
        $stub->bind(['id' => '2']);

        $validation = $stub->validate($input, $phrases);

        $this->assertInstanceOf('Illuminate\Contracts\Validation\Validator', $validation);
        $this->assertTrue($validation->passes());
    }
}

class FooValidator extends \Orchestra\Support\Validator
{
    protected $rules = [
        'email' => ['email'],
        'name' => ['required'],
    ];

    protected $phrases = [
        'email.required' => 'Email required',
    ];

    protected function onOptionalName($value)
    {
        $this->rules['name'] = ['sometimes'];
    }

    protected function extendOptionalName(ValidatorContract $validator)
    {
        //
    }
}
