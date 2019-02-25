<?php

namespace Orchestra\Support\Concerns;

use Orchestra\Support\Str;
use Illuminate\Support\Fluent;
use Illuminate\Contracts\Validation\Validator;

trait Validation
{
    /**
     * The validation factory implementation.
     *
     * @var \Illuminate\Contracts\Validation\Factory
     */
    protected $validationFactory;

    /**
     * The event dispatcher implementation.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $validationDispatcher;

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

        $this->validationScenarios = \compact('on', 'extend', 'parameters');

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
        $this->validationBindings = \array_merge($this->validationBindings, $bindings);

        return $this;
    }

    /**
     * Execute validation service.
     *
     * @param  array  $input
     * @param  string|array  $events
     * @param  array  $phrases
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    final public function runValidation(array $input, $events = [], array $phrases = []): Validator
    {
        if (\is_null($this->validationScenarios)) {
            $this->onValidationScenario('any');
        }

        $this->runOnScenario();

        [$rules, $phrases] = $this->runValidationEvents($events, $phrases);

        return \tap($this->validationFactory->make($input, $rules, $phrases), function ($validator) {
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
                $rules[$key] = Str::replace($value, $this->validationBindings);
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
    final protected function runExtendedScenario(Validator $validator): void
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
        \is_array($events) || $events = (array) $events;

        // Merge all the events.
        $events = \array_merge($this->getValidationEvents(), $events);

        // Convert rules array to Fluent, in order to pass it by references
        // in all event listening to this validation.
        $rules = new Fluent($this->getBindedRules());
        $phrases = new Fluent(\array_merge($this->getValidationPhrases(), $phrases));

        foreach ((array) $events as $event) {
            $this->validationDispatcher->dispatch($event, [&$rules, &$phrases]);
        }

        return [
            $rules->getAttributes(), $phrases->getAttributes(),
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
        $scenario = \ucfirst($scenario);

        $on = "on{$scenario}";
        $extend = "extend{$scenario}";

        return [
            \method_exists($this, $on) ? $on : null,
            \method_exists($this, $extend) ? $extend : null,
        ];
    }
}
