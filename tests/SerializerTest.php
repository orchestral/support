<?php

namespace Orchestra\Support\TestCase;

use Orchestra\Support\Fluent;
use PHPUnit\Framework\TestCase;
use Orchestra\Support\Serializer;
use Illuminate\Support\Collection;

class SerializerTest extends TestCase
{
    /** @test */
    public function it_can_serialize_single_dataset()
    {
        $dataset = new Fluent([
            'fullname' => 'Mior Muhammad Zaki', 'email' => 'crynobone@gmail.com',
        ]);

        $serializer = new class() extends Serializer {
            protected $key = 'user';
        };

        $this->assertSame([
            'user' => [
                'fullname' => 'Mior Muhammad Zaki',
                'email' => 'crynobone@gmail.com',
            ],
        ], $serializer($dataset));
    }

    /** @test */
    public function it_can_serialize_dataset_collection()
    {
        $dataset = new Collection(
            [
                new Fluent([
                    'fullname' => 'Mior Muhammad Zaki', 'email' => 'crynobone@gmail.com',
                ]),
                new Fluent([
                    'fullname' => 'Miki', 'email' => 'hello@orchestraplatform.com',
                ])
            ]
        );

        $serializer = new class() extends Serializer {
            protected $key = 'user';
        };

        $this->assertSame([
            'users' => [
                [
                    'fullname' => 'Mior Muhammad Zaki',
                    'email' => 'crynobone@gmail.com',
                ],
                [
                    'fullname' => 'Miki',
                    'email' => 'hello@orchestraplatform.com',
                ],
            ],
        ], $serializer($dataset));
    }
}
