<?php

namespace Orchestra\Support\TestCase;

use Orchestra\Support\Str;
use PHPUnit\Framework\TestCase;

class StrTest extends TestCase
{
    /** @test */
    function it_can_humanize_string()
    {
        $expected = 'Foobar Is Awesome';
        $output = Str::humanize('foobar-is-awesome');

        $this->assertEquals($expected, $output);
    }

    /** @test */
    function it_can_replace_string()
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
    function it_can_convert_to_searchable_string()
    {
        $expected = ['foobar%'];
        $output = Str::searchable('foobar*');

        $this->assertEquals($expected, $output);

        $expected = ['foobar', 'foobar%', '%foobar', '%foobar%'];
        $output = Str::searchable('foobar');

        $this->assertEquals($expected, $output);
    }

    /** @test */
    function it_can_convert_stream_get_contents_string()
    {
        $base_path = __DIR__.'/stub/';
        $expected = 'a:2:{s:4:"name";s:9:"Orchestra";s:5:"theme";a:2:{s:7:"backend";s:7:"default";s:8:"frontend";s:7:"default";}}';
        $stream = fopen($base_path.'driver1.stub.php', 'r');
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
        $stream = fopen($base_path.'driver2.stub.php', 'r');
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
    function it_can_convert_utf8_string_to_title_case()
    {
        $this->assertEquals('Taylor', Str::title('taylor'));
        $this->assertEquals('Άχιστη', Str::title('άχιστη'));
    }
}
