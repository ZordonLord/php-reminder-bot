<?php
use PHPUnit\Framework\TestCase;
use App\EventSender\EventSender;
use App\Application;

class EventSenderTest extends TestCase
{
    public function testSendMessageEchoesSuccess()
    {
        $app = $this->createMock(Application::class);
        $sender = $this->getMockBuilder(EventSender::class)
            ->setConstructorArgs([$app])
            ->onlyMethods(['sendMessage'])
            ->getMock();
        $sender->expects($this->once())
            ->method('sendMessage')
            ->with('123', 'test');
        $sender->sendMessage('123', 'test');
    }
} 