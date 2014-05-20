<?php namespace Orchestra\Support\TestCase;

use Orchestra\Support\Ftp\Morph;
use Orchestra\Support\Ftp;

class FtpTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Stub instance.
     *
     * @var Orchestra\Support\Ftp
     */
    private $stub = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        Morph::$prefix = '\Orchestra\Support\TestCase\mock1_ftp_';

        $this->stub = new Ftp(array(
            'host'     => 'sftp://localhost:22',
            'user'     => 'foo',
            'password' => 'foobar',
        ));
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        Morph::$prefix = 'ftp_';
    }

    /**
     * Test instance of Orchestra\Support\Ftp.
     *
     * @test
     */
    public function testInstanceOfFTP()
    {
        Morph::$prefix = '\Orchestra\Support\TestCase\mock1_ftp_';

        $stub = Ftp::make(array('connection' => new StreamStub));

        $this->assertInstanceOf('\Orchestra\Support\Ftp', $stub);

        $this->assertTrue($stub->connected());
        $stub->close();
        $this->assertFalse($stub->connected());

        $refl   = new \ReflectionObject($this->stub);
        $config = $refl->getProperty('config');

        $config->setAccessible(true);

        $configuration = $config->getValue($this->stub);

        $this->assertEquals('localhost', $configuration['host']);
        $this->assertTrue($configuration['ssl']);
        $this->assertEquals('22', $configuration['port']);
        $this->assertEquals('foo', $configuration['user']);
        $this->assertEquals('foobar', $configuration['password']);
    }

    /**
     * Test Orchestra\Support\Ftp::connect() method successful.
     *
     * @test
     */
    public function testConnectMethodSuccessful()
    {
        Morph::$prefix = '\Orchestra\Support\TestCase\mock1_ftp_';

        $this->assertFalse($this->stub->connected());
        $this->assertTrue($this->stub->connect());
        $this->assertTrue($this->stub->connected());

        $stub = new Ftp(array(
            'host'     => 'ftp://localhost:21',
            'user'     => 'foo',
            'password' => 'foobar',
        ));

        $this->assertFalse($stub->connected());
        $this->assertTrue($stub->connect());
        $this->assertTrue($stub->connected());
    }

    /**
     * Test Orchestra\Support\Ftp::connect() method when host is not defined.
     *
     * @test
     */
    public function testConnectMethodWhenHostIsNotDefined()
    {
        Morph::$prefix = '\Orchestra\Support\TestCase\mock1_ftp_';

        $stub = new Ftp(array(
            'user'     => 'foo',
            'password' => 'foobar',
        ));

        $this->assertNull($stub->connect());
    }

    /**
     * Test Orchestra\Support\Ftp::connect() method with ftp_ssl_connect() throws exception.
     *
     * @expectedException Orchestra\Support\Ftp\ServerException
     */
    public function testConnectMethodSFTPConnectThrowsException()
    {
        Morph::$prefix = '\Orchestra\Support\TestCase\mock2_ftp_';

        $stub = new Ftp(array(
            'host'     => 'sftp://localhost:22',
            'user'     => 'foo',
            'password' => 'foobar',
        ));

        $stub->connect();
    }

    /**
     * Test Orchestra\Support\Ftp::connect() method with ftp_connect() throws exception.
     *
     * @expectedException Orchestra\Support\Ftp\ServerException
     */
    public function testConnectMethodFTPConnectThrowsException()
    {
        Morph::$prefix = '\Orchestra\Support\TestCase\mock2_ftp_';

        $stub = new Ftp(array(
            'host'     => 'ftp://localhost:21',
            'user'     => 'foo',
            'password' => 'foobar',
        ));

        $stub->connect();
    }

    /**
     * Test Orchestra\Support\Ftp::connect() method with ftp_login() throws exception.
     *
     * @expectedException Orchestra\Support\Ftp\ServerException
     */
    public function testConnectMethodFTPLoginThrowsException()
    {
        Morph::$prefix = '\Orchestra\Support\TestCase\mock3_ftp_';

        $stub = new Ftp(array(
            'host'     => 'ftp://localhost:21',
            'user'     => 'foo',
            'password' => 'foobar',
        ));

        $stub->connect();
    }

    /**
     * Test Orchestra\Support\Ftp\Morph methods.
     *
     * @test
     */
    public function testFTPFacadeMethodsSuccessful()
    {
        Morph::$prefix = '\Orchestra\Support\TestCase\mock1_ftp_';

        $this->stub->connect();

        $this->assertEquals('/var/www/', $this->stub->currentDirectory());
        $this->assertTrue($this->stub->changeDirectory('/var/www/'));
        $this->assertTrue($this->stub->get('/var/www/home.php', '/var/www/home.php'));
        $this->assertTrue($this->stub->put('/var/www/home.php', '/var/www/home.php'));
        $this->assertTrue($this->stub->rename('/var/www/home.php', '/var/www/dashboard.php'));
        $this->assertTrue($this->stub->delete('/var/www/home.php'));
        $this->assertTrue($this->stub->permission('/var/www/index.php', 755));
        $this->assertEquals(array('foo.php', 'foobar.php'), $this->stub->allFiles('/var/www/foo/'));
        $this->assertTrue($this->stub->makeDirectory('/var/www/orchestra'));
        $this->assertTrue($this->stub->removeDirectory('/var/www/orchestra'));
    }

    /**
     * Test Orchestra\Support\Ftp\Morph method throws Exception.
     *
     * @test
     */
    public function testFTPFacadeThrowsException()
    {
        Morph::$prefix = '\Orchestra\Support\TestCase\mock1_ftp_';

        try {
            Morph::fire('invalid_method', array('foo'));
        } catch (\Orchestra\Support\Ftp\RuntimeException $e) {
            $this->assertTrue(true, 'Excepted Exception');
            $this->assertEquals(array('foo'), $e->getParameters());
        }
    }
}

class StreamStub
{

}

function mock1_ftp_ssl_connect()
{
    return new StreamStub;
}

function mock1_ftp_connect()
{
    return new StreamStub;
}

function mock1_ftp_login()
{
    return true;
}

function mock1_ftp_close()
{
    return true;
}

function mock1_ftp_pasv()
{
    return true;
}

function mock1_ftp_pwd()
{
    return '/var/www/';
}

function mock1_ftp_systype()
{
    return 'unix';
}

function mock1_ftp_chdir()
{
    return true;
}

function mock1_ftp_get()
{
    return true;
}

function mock1_ftp_put()
{
    return true;
}

function mock1_ftp_rename()
{
    return true;
}

function mock1_ftp_delete()
{
    return true;
}

function mock1_ftp_chmod()
{
    return true;
}

function mock1_ftp_nlist()
{
    return array('foo.php', 'foobar.php');
}

function mock1_ftp_mkdir()
{
    return true;
}

function mock1_ftp_rmdir()
{
    return true;
}

function mock2_ftp_ssl_connect()
{
    return false;
}

function mock2_ftp_connect()
{
    return false;
}

function mock3_ftp_ssl_connect()
{
    return new StreamStub;
}

function mock3_ftp_connect()
{
    return new StreamStub;
}

function mock3_ftp_login()
{
    return false;
}
