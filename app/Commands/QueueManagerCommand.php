<?php
namespace App\Commands;

use App\Application;
use App\Queue\Queueable;
use App\Queue\QueueMock;
use App\EventSender\EventSender;
use App\EventSender\TelegramApi;

class QueueManagerCommand extends Command
{
    protected Application $app;
    public function __construct(Application $app)
    {
        $this->app = $app;
    }
    public function run(array $options = []): void
    {
        $queue = new QueueMock(); 
        $telegramApi = new TelegramApi($this->app->env('TELEGRAM_BOT_TOKEN'));
        $eventSender = new EventSender($telegramApi, $queue);
        while (true) {
            $eventSender->handle();
            sleep(10);
        }
    }
} 