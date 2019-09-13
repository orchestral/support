<?php

namespace Orchestra\Support\Tests;

use Orchestra\Support\Fluent;
use Orchestra\Support\Transformer;
use PHPUnit\Framework\TestCase;

class TransformerTest extends TestCase
{
    /** @test */
    public function it_can_be_transformed()
    {
        $given = new Fluent([
            'email' => 'demo@orchestraplatform.com',
            'first_name' => 'Mior Muhammad Zaki',
            'last_name' => 'Mior Khairuddin',
        ]);

        $expected = new Fluent([
            'email' => 'demo@orchestraplatform.com',
            'name' => 'Mior Muhammad Zaki Mior Khairuddin',
        ]);

        $this->assertEquals($expected, DemoTransformer::make($given));
    }
}

class DemoTransformer extends Transformer
{
    public function transform($entity)
    {
        return [
            'email' => $entity->email,
            'name' => sprintf('%s %s', $entity->first_name, $entity->last_name),
        ];
    }
}
