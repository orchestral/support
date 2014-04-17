<?php namespace Orchestra\Support\Traits\TestCase;

use Mockery as m;
use Orchestra\Support\Traits\QueryFilterTrait;

class QueryFilterTraitTest extends \PHPUnit_Framework_TestCase
{
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
        $stub = new QueryFilter;
        $query = m::mock('\Illuminate\Database\Query\Builder');

        $query->shouldReceive('orderBy')->once()->with('updated_at', 'DESC')->andReturn($query);

        $this->assertInstanceOf('\Illuminate\Database\Query\Builder', $stub->stubSetupBasicQueryFilter($query, [
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
        $stub = new QueryFilter;
        $query = m::mock('\Illuminate\Database\Query\Builder');

        $query->shouldReceive('where')->once()->with(m::type('Closure'))
                ->andReturnUsing(function ($c) use ($query) {
                    $c($query);
                })
            ->shouldReceive('orWhere')->once()->with('name', 'LIKE', 'hello')
            ->shouldReceive('orWhere')->once()->with('name', 'LIKE', 'hello%')
            ->shouldReceive('orWhere')->once()->with('name', 'LIKE', '%hello')
            ->shouldReceive('orWhere')->once()->with('name', 'LIKE', '%hello%');

        $this->assertInstanceOf('\Illuminate\Database\Query\Builder', $stub->stubSetupWildcardQueryFilter($query, 'hello', ['name']));
    }
}

class QueryFilter
{
    use QueryFilterTrait;

    public function stubSetupBasicQueryFilter($query, $input)
    {
        return $this->setupBasicQueryFilter($query, $input);
    }

    public function stubSetupWildcardQueryFilter($query, $keyword, $fields)
    {
        return $this->setupWildcardQueryFilter($query, $keyword, $fields);
    }
}
