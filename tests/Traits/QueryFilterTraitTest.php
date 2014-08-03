<?php namespace Orchestra\Support\Traits\TestCase;

use Mockery as m;
use Orchestra\Support\Traits\QueryFilterTrait;

class QueryFilterTraitTest extends \PHPUnit_Framework_TestCase
{
    use QueryFilterTrait;

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test \Orchestra\Support\Traits\QueryFilterTrait::setupBasicQueryFilter()
     * method.
     *
     * @test
     */
    public function testSetupBasicQueryFilterMethod()
    {
        $query = m::mock('\Illuminate\Database\Query\Builder');

        $query->shouldReceive('orderBy')->once()->with('updated_at', 'DESC')->andReturn($query);

        $this->assertEquals($query, $this->setupBasicQueryFilter($query, [
            'order' => 'updated',
            'sort'  => 'desc',
        ]));
    }

    /**
     * Test \Orchestra\Support\Traits\QueryFilterTrait::setupWildcardQueryFilter()
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
            ->shouldReceive('orWhere')->once()->with('name', 'LIKE', 'hello')
            ->shouldReceive('orWhere')->once()->with('name', 'LIKE', 'hello%')
            ->shouldReceive('orWhere')->once()->with('name', 'LIKE', '%hello')
            ->shouldReceive('orWhere')->once()->with('name', 'LIKE', '%hello%');

        $this->assertEquals($query, $this->setupWildcardQueryFilter($query, 'hello', ['name']));
    }
}
