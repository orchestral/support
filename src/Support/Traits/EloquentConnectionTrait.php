<?php namespace Orchestra\Support\Traits;

use Mockery as m;

trait EloquentConnectionTrait
{
    /**
     * Set mock connection
     */
    protected function addMockConnection($model)
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