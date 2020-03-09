<?php

namespace Orchestra\Support\Tests;

use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Event;
use Mockery as m;
use Orchestra\Testbench\TestCase;

class ValidatorTest extends TestCase
{
    /** @test */
    public function it_can_validate()
    {
        Event::shouldReceive('dispatch')->once()->with('foo.event', m::any())->andReturnNull();

        $input = ['email' => 'crynobone@gmail.com'];
        $phrases = ['email.required' => 'Email required'];

        $stub = new FooValidator(app(ValidationFactory::class), app('events'));
        $stub->on('optional-name', ['orchestra'])->bind(['id' => '2']);

        $validation = $stub->with($input, 'foo.event');

        $this->assertInstanceOf('Illuminate\Contracts\Validation\Validator', $validation);
        $this->assertTrue($validation->passes());
    }

    /** @test */
    public function it_can_validate_without_scope()
    {
        $validator = m::mock('Illuminate\Contracts\Validation\Factory');

        $input = ['email' => 'crynobone@gmail.com', 'name' => 'Mior Muhammad Zaki'];
        $phrases = ['email.required' => 'Email required'];

        $stub = new FooValidator(app(ValidationFactory::class), app('events'));
        $stub->bind(['id' => '2']);

        $validation = $stub->with($input);

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

    protected function extendOptionalName(Validator $validator)
    {
        //
    }
}
