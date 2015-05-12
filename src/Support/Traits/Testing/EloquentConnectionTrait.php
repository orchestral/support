<?php namespace Orchestra\Support\Traits\Testing;

use Mockery as m;
use Illuminate\Database\Eloquent\Model;

trait EloquentConnectionTrait
{
    /**
     * Set mock connection.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     */
    protected function addMockConnection(Model $model)
    {
        $resolver = m::mock('\Illuminate\Database\ConnectionResolverInterface');
        $model->setConnectionResolver($resolver);
        $resolver->shouldReceive('connection')
            ->andReturn(m::mock('\Illuminate\Database\Connection'));
        $model->getConnection()
            ->shouldReceive('getQueryGrammar')
            ->andReturn(m::mock('\Illuminate\Database\Query\Grammars\Grammar'));
        $model->getConnection()
            ->shouldReceive('getPostProcessor')
            ->andReturn(m::mock('\Illuminate\Database\Query\Processors\Processor'));
    }
}
