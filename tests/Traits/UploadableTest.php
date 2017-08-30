<?php

namespace Orchestra\Support\TestCase\Traits;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Facade;
use Orchestra\Support\Traits\Uploadable;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadableTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication(new Container());
    }

    /**
     * Teardown the test environment.
     */
    protected function tearDown()
    {
        m::close();
    }

    /** @test */
    function it_can_save_uploaded_file()
    {
        $path = '/var/www/public/';
        $file = m::mock('\Symfony\Component\HttpFoundation\File\UploadedFile[getClientOriginalExtension,move]', [
            realpath(__DIR__.'/fixtures').'/test.gif',
            'test',
        ]);

        $file->shouldReceive('getClientOriginalExtension')->once()->andReturn('jpg')
            ->shouldReceive('move')->once()->with($path, m::type('String'))->andReturnNull();

        $filename = (new UploadedStub())->save($file, $path);
    }

    /** @test */
    function it_can_save_uploaded_file_with_custom_filename()
    {
        $path = '/var/www/public/';
        $file = m::mock('\Symfony\Component\HttpFoundation\File\UploadedFile[move]', [
            realpath(__DIR__.'/fixtures').'/test.gif',
            'test',
        ]);

        $file->shouldReceive('move')->once()->with($path, 'foo.jpg')->andReturnNull();

        $filename = (new UploadedStubWithReplacement())->save($file, $path);
    }

    /** @test */
    function it_can_delete_uploaded_file()
    {
        $filesystem = m::mock('\Illuminate\Filesystem\Filesystem');
        $filename = '/var/www/foo.jpg';

        $filesystem->shouldReceive('delete')->once()->with($filename)->andReturn(true);

        File::swap($filesystem);

        $this->assertTrue((new UploadedStub())->delete($filename));
    }
}

class UploadedStub
{
    use Uploadable;

    public function save(UploadedFile $file, $path)
    {
        return $this->saveUploadedFile($file, $path);
    }

    public function delete($file)
    {
        return $this->deleteUploadedFile($file);
    }
}

class UploadedStubWithReplacement
{
    use \Orchestra\Support\Traits\Uploadable;

    public function save(UploadedFile $file, $path)
    {
        return $this->saveUploadedFile($file, $path);
    }

    public function delete($file)
    {
        return $this->deleteUploadedFile($file);
    }

    protected function getUploadedFilename(UploadedFile $file)
    {
        return 'foo.jpg';
    }
}
