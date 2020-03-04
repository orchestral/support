<?php

namespace Orchestra\Support\Tests;

use Orchestra\Support\Str;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\Environment\OperatingSystem;

class StrTest extends TestCase
{
    /** @test */
    public function it_can_humanize_string()
    {
        $expected = 'Foobar Is Awesome';
        $output = Str::humanize('foobar-is-awesome');

        $this->assertEquals($expected, $output);
    }

    /** @test */
    public function it_can_replace_string()
    {
        $expected = 'Orchestra Platform is awesome';
        $output = Str::replace('{name} is awesome', ['name' => 'Orchestra Platform']);

        $this->assertEquals($expected, $output);

        $expected = [
            'Orchestra Platform is awesome',
            'Orchestra Platform is not a foobar',
        ];

        $data = [
            '{name} is awesome',
            '{name} is not a {foo}',
        ];
        $output = Str::replace($data, ['name' => 'Orchestra Platform', 'foo' => 'foobar']);

        $this->assertEquals($expected, $output);
    }

    /** @test */
    public function it_can_convert_to_searchable_string()
    {
        $expected = ['foobar%'];
        $output = Str::searchable('foobar*');

        $this->assertEquals($expected, $output);

        $expected = ['foobar', 'foobar%', '%foobar', '%foobar%'];
        $output = Str::searchable('foobar');

        $this->assertEquals($expected, $output);
    }

    /** @test */
    public function it_can_convert_stream_get_contents_string()
    {
        if ((new OperatingSystem())->getFamily() === 'Windows') {
            $this->markTestSkipped(
                'Unable to tests on Windows environment.'
            );
        }

        $basePath = __DIR__.'/Stubs/';
        $expected = 'a:2:{s:4:"name";s:9:"Orchestra";s:5:"theme";a:2:{s:7:"backend";s:7:"default";s:8:"frontend";s:7:"default";}}';
        $stream = fopen($basePath.'driver1.stub', 'r');
        $output = Str::streamGetContents($stream);

        $this->assertEquals($expected, $output);

        $expected = [
            'name' => 'Orchestra',
            'theme' => [
                'backend' => 'default',
                'frontend' => 'default',
            ],
        ];

        $this->assertEquals($expected, unserialize($output));

        $expected = 'a:2:{s:4:"name";s:9:"Orchestra";s:5:"theme";a:2:{s:7:"backend";s:7:"default";s:8:"frontend";s:7:"default";}}'."\n";
        $stream = fopen($basePath.'driver2.stub', 'r');
        $output = Str::streamGetContents($stream);

        $this->assertEquals($expected, $output);

        $expected = [
            'name' => 'Orchestra',
            'theme' => [
                'backend' => 'default',
                'frontend' => 'default',
            ],
        ];

        $this->assertEquals($expected, unserialize($output));
        $this->assertEquals('foo', Str::streamGetContents('foo'));
    }

    /** @test */
    public function it_can_convert_utf8_string_to_title_case()
    {
        $this->assertEquals('Taylor', Str::title('taylor'));
        $this->assertEquals('Άχιστη', Str::title('άχιστη'));
    }

    /** @test */
    public function it_can_validate_column_name()
    {
        $this->assertTrue(Str::validateColumnName('email'));
        $this->assertTrue(Str::validateColumnName(str_pad('email', 64, 'x')));

        $this->assertFalse(Str::validateColumnName('email->"%27))%23injectedSQL'));
        $this->assertFalse(Str::validateColumnName(str_pad('email', 65, 'x')));
        $this->assertFalse(Str::validateColumnName(null));
    }
}
