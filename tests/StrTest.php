<?php
use PHPUnit\Framework\TestCase;
use App\Helpers\Str;

class StrTest extends TestCase
{
    /**
     * @dataProvider studlyProvider
     */
    public function testStudly($input, $expected)
    {
        $this->assertEquals($expected, Str::studly($input));
    }

    public static function studlyProvider()
    {
        return [
            ['hello_world', 'HelloWorld'],
            ['foo-bar', 'FooBar'],
            ['test string', 'TestString'],
            ['alreadyStudly', 'Alreadystudly'],
        ];
    }

    public function testCamel()
    {
        $this->assertEquals('Alreadystudly', Str::camel('alreadyStudly'));
        $this->assertEquals('HelloWorld', Str::camel('hello_world'));
    }
} 