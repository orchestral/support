<?php

namespace Orchestra\Support\TestCase\Traits;

use PHPUnit\Framework\TestCase;
use Orchestra\Support\Traits\DataContainer;

class DataContainerTest extends TestCase
{
    use DataContainer;

    /** @test */
    public function it_can_get_all_with_removed()
    {
        $this->items = [
            'fullname' => 'Mior Muhammad Zaki',
            'emails' => [
                'personal' => 'crynobone@gmail.com',
                'work' => 'crynobone@katsana.com',
            ],
        ];

        $this->forget('emails.work');

        $this->assertSame([
            'fullname' => 'Mior Muhammad Zaki',
            'emails' => [
                'personal' => 'crynobone@gmail.com',
                'work' => null,
            ],
        ], $this->allWithRemoved());
    }
}
