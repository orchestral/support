<?php namespace Orchestra\Support\Tests;

use Mockery as m;
use Orchestra\Support\Messages;

class MessagesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Support\Messages::make() method.
     *
     * @test
     */
    public function testMakeMethod()
    {
        $session = m::mock('\Illuminate\Session\Store');

        $message = with(new Messages)->setSession($session);
        $message->add('welcome', 'Hello world');
        $message->setFormat();

        $this->assertInstanceOf('\Orchestra\Support\Messages', $message);
        $this->assertEquals(array('Hello world'), $message->get('welcome'));

        $message->add('welcome', 'Hi Foobar')->add('welcome', 'Heya Admin');
        $this->assertEquals(array('Hello world', 'Hi Foobar', 'Heya Admin'), $message->get('welcome'));

        $this->assertEquals($session, $message->getSession());
    }

    /**
     * Test Orchestra\Support\Messages::save() method.
     *
     * @test
     */
    public function testSaveMethod()
    {
        $session = m::mock('\Illuminate\Session\Store');
        $session->shouldReceive('flash')->once()->andReturn(true);

        with(new Messages)->setSession($session)->save();
    }

    /**
     * Test serializing and storing Orchestra\Support\Messages over
     * Session
     *
     * @test
     */
    public function testStoreMethod()
    {
        $session = m::mock('\Illuminate\Session\Store');

        $session->shouldReceive('flash')->once()->andReturn(true);

        $message = with(new Messages)->setSession($session);
        $message->add('hello', 'Hi World');
        $message->add('bye', 'Goodbye');

        $serialize = $message->serialize();

        $this->assertTrue(is_string($serialize));
        $this->assertContains('hello', $serialize);
        $this->assertContains('Hi World', $serialize);
        $this->assertContains('bye', $serialize);
        $this->assertContains('Goodbye', $serialize);

        $message->save();
    }

    /**
     * Test un-serializing and retrieving Orchestra\Support\Messages over
     * Session
     *
     * @test
     */
    public function testRetrieveMethod()
    {
        $session = m::mock('\Illuminate\Session\Store');
        $session->shouldReceive('has')->once()->andReturn(true)
            ->shouldReceive('get')->once()
                ->andReturn('a:2:{s:5:"hello";a:1:{i:0;s:8:"Hi World";}s:3:"bye";a:1:{i:0;s:7:"Goodbye";}}')
            ->shouldReceive('forget')->once()->andReturn(true);

        $retrieve = with(new Messages)->setSession($session)->retrieve();
        $retrieve->setFormat();

        $this->assertInstanceOf('\Orchestra\Support\Messages', $retrieve);
        $this->assertEquals(array('Hi World'), $retrieve->get('hello'));
        $this->assertEquals(array('Goodbye'), $retrieve->get('bye'));
    }

    /**
     * Test un-serializing and extending Orchestra\Support\Messages over
     * Session
     *
     * @test
     */
    public function testExtendMethod()
    {
        $session = m::mock('\Illuminate\Session\Store');
        $session->shouldReceive('has')->once()->andReturn(true)
            ->shouldReceive('get')->once()
                ->andReturn('a:1:{s:5:"hello";a:1:{i:0;s:8:"Hi World";}}')
            ->shouldReceive('forget')->once()->andReturn(true);

        $callback = function ($msg) {
            $msg->add('hello', 'Hi Orchestra Platform');
        };

        $stub = with(new Messages)->setSession($session);
        $stub->extend($callback);

        $retrieve = $stub->retrieve();
        $retrieve->setFormat();

        $this->assertInstanceOf('\Orchestra\Support\Messages', $retrieve);
        $this->assertEquals(array('Hi World', 'Hi Orchestra Platform'), $retrieve->get('hello'));
    }
}
