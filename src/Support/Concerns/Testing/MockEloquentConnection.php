<?php

namespace Orchestra\Support\Concerns\Testing;

use Illuminate\Database\Eloquent\Model;
use Mockery as m;

trait MockEloquentConnection
{
    /**
     * Set mock connection.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     *
     * @return void
     */
    protected function addMockConnection(Model $model): void
    {
        $resolver = m::mock('Illuminate\Database\ConnectionResolverInterface');
        $model->setConnectionResolver($resolver);
        $resolver->shouldReceive('connection')
            ->andReturn(m::mock('Illuminate\Database\Connection'));
        $model->getConnection()
            ->shouldReceive('getQueryGrammar')
            ->andReturn(m::mock('Illuminate\Database\Query\Grammars\Grammar'));
        $model->getConnection()
            ->shouldReceive('getPostProcessor')
            ->andReturn(m::mock('Illuminate\Database\Query\Processors\Processor'));
    }
}
