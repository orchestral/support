<?php

namespace Orchestra\Support\Tests\Concerns;

use PHPUnit\Framework\TestCase;
use Orchestra\Support\Concerns\DataContainer;

class DataContainerTest extends TestCase
{
    use DataContainer;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        $this->items = [
            'fullname' => 'Mior Muhammad Zaki',
            'emails' => [
                'personal' => 'crynobone@gmail.com',
                'work' => 'crynobone@katsana.com',
            ],
        ];
    }

    /** @test */
    public function it_can_get_items()
    {
        $this->assertSame('Mior Muhammad Zaki', $this->get('fullname'));
        $this->assertSame('crynobone@katsana.com', $this->get('emails.work'));
        $this->assertSame('crynobone+home@gmail.com', $this->get('emails.home', 'crynobone+home@gmail.com'));
    }

    /** @test */
    public function it_can_set_items()
    {
        $this->set('fullname', 'Mior Muhammad Zaki Mior Khairuddin');
        $this->set('email.personal', 'crynobone+personal@gmail.com');

        $this->assertSame('crynobone+personal@gmail.com', $this->items['email']['personal']);
    }

    /** @test */
    public function it_can_check_if_item_exists()
    {
        $this->assertTrue($this->has('fullname'));
        $this->assertTrue($this->has('emails.work'));
        $this->assertFalse($this->has('emails.home'));
    }

    /** @test */
    public function it_can_get_all_with_removed()
    {
        $this->forget('emails.work');

        $this->assertSame([
            'fullname' => 'Mior Muhammad Zaki',
            'emails' => [
                'personal' => 'crynobone@gmail.com',
                'work' => null,
            ],
        ], $this->allWithRemoved());

        $this->assertSame(['emails.work'], $this->removedItems);
    }
}
