<?php
use PHPUnit\Framework\TestCase;
use App\Helpers\Str;

require_once __DIR__ . '/StrDataProvider.php';

class StrTest extends TestCase
{
    /**
     * @dataProvider StrDataProvider::studlyProvider
     */
    public function testStudly($input, $expected)
    {
        $this->assertEquals($expected, Str::studly($input));
    }

    public function testCamel()
    {
        $this->assertEquals('Alreadystudly', Str::camel('alreadyStudly'));
        $this->assertEquals('HelloWorld', Str::camel('hello_world'));
    }
} 