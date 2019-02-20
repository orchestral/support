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
        list($on, $extend) = $this->getValidationSchemasName($scenario);

        $this->validationScenarios = [
            'on' => \method_exists($this, $on) ? $on : null,
            'extend' => \method_exists($this, $extend) ? $extend : null,
            'parameters' => $parameters,
        ];

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

        list($rules, $phrases) = $this->runValidationEvents($events, $phrases);

        $validationResolver = $this->validationFactory->make($input, $rules, $phrases);

        $this->runExtendedScenario($validationResolver);

        return $validationResolver;
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
        if (! \is_null($method = $this->validationScenarios['on'])) {
            $this->{$method}(...$this->validationScenarios['parameters']);
        }
    }

    /**
     * Run queued extend scenario.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validationResolver
     *
     * @return void
     */
    final protected function runExtendedScenario(Validator $validationResolver): void
    {
        if (! \is_null($method = $this->validationScenarios['extend'])) {
            $this->{$method}($validationResolver);
        }
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
        $on = 'on'.\ucfirst($scenario);
        $extend = 'extend'.\ucfirst($scenario);

        return [$on, $extend];
    }
}
