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
     * Test \Orchestra\Support\Traits\QueryFilterTrait is executable.
     *
     * @test
     */
    public function testMacroIsExecutable()
    {
        $stub = new QueryFilter;
        $query = m::mock('\Illuminate\Database\Query\Builder');

        $query->shouldReceive('orderBy')->once()->with('updated_at', 'DESC')->andReturn($query);

        $this->assertInstanceOf('\Illuminate\Database\Query\Builder', $stub->stub($query, [
            'order' => 'updated',
            'sort'  => 'desc',
        ]));
    }
}

class QueryFilter
{
    use QueryFilterTrait;

    public function stub($query, $input)
    {
        return $this->setupBasicQueryFilter($query, $input);
    }
}
