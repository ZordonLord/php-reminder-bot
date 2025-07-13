<?php
use PHPUnit\Framework\TestCase;
use App\Environment;
use App\Application;

class EnvironmentTest extends TestCase
{
    public function testGetReturnsEnvValue()
    {
        $_ENV['FOO'] = 'bar';
        $app = $this->createMock(Application::class);
        $env = new Environment($app);
        $this->assertEquals('bar', $env->get('FOO'));
    }

    public function testGetReturnsDefaultIfNotSet()
    {
        $app = $this->createMock(Application::class);
        $env = new Environment($app);
        $this->assertEquals('default', $env->get('NOT_SET', 'default'));
    }
} 