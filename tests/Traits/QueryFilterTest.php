<?php

namespace Orchestra\Support\TestCase\Traits;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Orchestra\Support\Traits\QueryFilter;

class QueryFilterTest extends TestCase
{
    use QueryFilter;

    /**
     * Teardown the test environment.
     */
    protected function tearDown()
    {
        m::close();
    }

    /** @test */
    function it_can_build_basic_query_filter()
    {
        $query = m::mock('\Illuminate\Database\Query\Builder');

        $query->shouldReceive('orderBy')->once()->with('updated_at', 'DESC')->andReturn($query)
            ->shouldReceive('orderBy')->once()->with('created_at', 'DESC')->andReturn($query);

        $this->assertEquals($query, $this->setupBasicQueryFilter($query, [
            'order_by' => 'updated',
            'direction' => 'desc',
        ]));

        $this->assertEquals($query, $this->setupBasicQueryFilter($query, [
            'order_by' => 'created',
            'direction' => 'desc',
            'columns' => ['only' => 'created_at'],
        ]));
    }

    /** @test */
    function it_can_build_basic_query_filter_given_column_excluded()
    {
        $query = m::mock('\Illuminate\Database\Query\Builder');

        $query->shouldReceive('orderBy')->never()->with('password', 'DESC')->andReturn($query);

        $this->assertEquals($query, $this->setupBasicQueryFilter($query, [
            'order_by' => 'password',
            'direction' => 'desc',
            'columns' => ['except' => 'password'],
        ]));
    }

    /** @test */
    function it_can_build_wildcard_query_filter_given_column_excluded()
    {
        $query = m::mock('\Illuminate\Database\Query\Builder');

        $query->shouldReceive('where')->once()->with(m::type('Closure'))
                ->andReturnUsing(function ($c) use ($query) {
                    $c($query);
                })
            ->shouldReceive('orWhere')->once()->with(m::type('Closure'))
                ->andReturnUsing(function ($c) use ($query) {
                    $c($query);
                })
            ->shouldReceive('orWhere')->once()->with('name', 'LIKE', 'hello')
            ->shouldReceive('orWhere')->once()->with('name', 'LIKE', 'hello%')
            ->shouldReceive('orWhere')->once()->with('name', 'LIKE', '%hello')
            ->shouldReceive('orWhere')->once()->with('name', 'LIKE', '%hello%');

        $this->assertEquals($query, $this->setupWildcardQueryFilter($query, 'hello', ['name']));
    }
}
