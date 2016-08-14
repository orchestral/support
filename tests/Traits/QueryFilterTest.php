<?php

namespace Orchestra\Support\TestCase\Traits;

use Mockery as m;
use Orchestra\Support\Traits\QueryFilter;

class QueryFilterTest extends \PHPUnit_Framework_TestCase
{
    use QueryFilter;

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test \Orchestra\Support\Traits\QueryFilter::setupBasicQueryFilter()
     * method.
     *
     * @test
     */
    public function testSetupBasicQueryFilterMethod()
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

    /**
     * Test \Orchestra\Support\Traits\QueryFilter::setupBasicQueryFilter()
     * method when column should be excluded.
     *
     * @test
     */
    public function testSetupBasicQueryFilterMethodGivenColumnExcluded()
    {
        $query = m::mock('\Illuminate\Database\Query\Builder');

        $query->shouldReceive('orderBy')->never()->with('password', 'DESC')->andReturn($query);

        $this->assertEquals($query, $this->setupBasicQueryFilter($query, [
            'order_by' => 'password',
            'direction' => 'desc',
            'columns' => ['except' => 'password'],
        ]));
    }

    /**
     * Test \Orchestra\Support\Traits\QueryFilter::setupWildcardQueryFilter()
     * method.
     *
     * @test
     */
    public function testSetupWildcardQueryFilterMethod()
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
