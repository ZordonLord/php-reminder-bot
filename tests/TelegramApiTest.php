<?php
use PHPUnit\Framework\TestCase;
use App\EventSender\TelegramApi;

class TelegramApiTest extends TestCase
{
    public function testConstructSetsTokenAndUrl()
    {
        $api = new TelegramApi('testtoken');
        $ref = new ReflectionClass($api);
        $token = $ref->getProperty('token');
        $token->setAccessible(true);
        $apiUrl = $ref->getProperty('apiUrl');
        $apiUrl->setAccessible(true);
        $this->assertEquals('testtoken', $token->getValue($api));
        $this->assertStringContainsString('testtoken', $apiUrl->getValue($api));
    }
} 