<?php

namespace Orchestra\Support\Concerns;

use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Fluent;
use Orchestra\Support\Arr;
use Orchestra\Support\Str;

trait Validation
{
    /**
     * List of bindings.
     *
     * @var array
     */
    protected $validationBindings = [];

    /**
     * Validation scenario configuration.
     *
     * @var array
     */
    protected $validationScenarios;

    /**
     * Create a scope scenario.
     *
     * @param  string  $scenario
     * @param  array   $parameters
     *
     * @return $this
     */
    final public function onValidationScenario(string $scenario, array $parameters = []): self
    {
        [$on, $extend] = $this->getValidationSchemasName($scenario);

        $this->validationScenarios = compact('on', 'extend', 'parameters');

        return $this;
    }

    /**
     * Add bindings.
     *
     * @param  array  $bindings
     *
     * @return $this
     */
    final public function bindToValidation(array $bindings): self
    {
        $this->validationBindings = array_merge($this->validationBindings, $bindings);

        return $this;
    }

    /**
     * Execute validation service.
     *
     * @param  \Illuminate\Http\Request|array  $request
     * @param  array  $phrases
     * @param  string|array  $events
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    final public function runValidation($request, array $phrases = [], $events = []): ValidatorContract
    {
        $input = $request instanceof Request ? $request->all() : $request;

        if (\is_null($this->validationScenarios)) {
            $this->onValidationScenario('any');
        }

        $this->runOnScenario();

        [$rules, $phrases] = $this->runValidationEvents($events, $phrases);

        return tap(Validator::make($input, $rules, $phrases), function (ValidatorContract $validator) {
            $this->runExtendedScenario($validator);
        });
    }

    /**
     * Run rules bindings.
     *
     * @return array
     */
    protected function getBindedRules(): array
    {
        $rules = $this->getValidationRules();

        if (! empty($this->validationBindings)) {
            foreach ($rules as $key => $value) {
                if (\is_string($value)) {
                    $rules[$key] = Str::replace($value, $this->validationBindings);
                }
            }
        }

        return $rules;
    }

    /**
     * Run queued on scenario.
     *
     * @return void
     */
    final protected function runOnScenario(): void
    {
        if (\is_null($method = $this->validationScenarios['on'])) {
            return;
        }

        $this->{$method}(...$this->validationScenarios['parameters']);
    }

    /**
     * Run queued extend scenario.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     *
     * @return void
     */
    final protected function runExtendedScenario(ValidatorContract $validator): void
    {
        if (\is_null($method = $this->validationScenarios['extend'])) {
            return;
        }

        $this->{$method}($validator);
    }

    /**
     * Run validation events and return the finalize rules and phrases.
     *
     * @param  array|string  $events
     * @param  array  $phrases
     *
     * @return array
     */
    final protected function runValidationEvents($events, array $phrases): array
    {
        // Merge all the events.
        $events = array_merge($this->getValidationEvents(), Arr::wrap($events));

        // Convert rules array to Fluent, in order to pass it by references
        // in all event listening to this validation.
        $rules = new Fluent($this->getBindedRules());
        $phrases = new Fluent(array_merge($this->getValidationPhrases(), $phrases));

        foreach ((array) $events as $event) {
            Event::dispatch($event, [&$rules, &$phrases]);
        }

        return [
            $rules->getAttributes(),
            $phrases->getAttributes(),
        ];
    }

    /**
     * Get validation events.
     *
     * @return array
     */
    public function getValidationEvents(): array
    {
        return [];
    }

    /**
     * Get validation phrases.
     *
     * @return array
     */
    public function getValidationPhrases(): array
    {
        return [];
    }

    /**
     * Get validation rules.
     *
     * @return array
     */
    public function getValidationRules(): array
    {
        return [];
    }

    /**
     * Get validation schemas name.
     *
     * @param  string  $scenario
     *
     * @return array
     */
    protected function getValidationSchemasName(string $scenario): array
    {
        $scenario = Str::camel($scenario);

        $on = "on{$scenario}";
        $extend = "extend{$scenario}";

        return [
            method_exists($this, $on) ? $on : null,
            method_exists($this, $extend) ? $extend : null,
        ];
    }
}
