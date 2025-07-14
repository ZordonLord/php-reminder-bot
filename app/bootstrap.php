<?php

use App\Application;
use App\Cache\RedisCache;

require __DIR__.'/../vendor/autoload.php';

$cache = new RedisCache(['host' => '127.0.0.1', 'port' => 6379]);

return new Application(dirname(__DIR__), $cache);