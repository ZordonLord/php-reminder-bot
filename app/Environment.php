<?php
namespace App;


use Dotenv\Dotenv;

class Environment
{
    private Application $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $dotenv = Dotenv::createImmutable(__DIR__.'/..');
        $dotenv->load();
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $_ENV[$key] ?? $default;
    }
}