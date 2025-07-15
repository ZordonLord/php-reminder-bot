<?php

namespace App\Commands;

use App\Application;
use App\Queue\QueueMock;
use App\EventSender\TelegramApi;

class HandleQueueCommand extends Command
{
    protected Application $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function run(array $options = []): void
    {
        $queue = new QueueMock(); 
        $token = $this->app->env('TELEGRAM_BOT_TOKEN');
        $api = new TelegramApi($token);

        while (true) {
            $msg = $queue->getMessage();
            if ($msg === null) {
                sleep(1);
                continue;
            }
            $data = json_decode($msg, true);
            if (isset($data['receiver'], $data['message'])) {
                $result = $api->sendMessage($data['receiver'], $data['message']);
                if ($result && isset($result['ok']) && $result['ok']) {
                    echo date('d.m.y H:i') . " Сообщение '{$data['message']}' отправлено получателю с id {$data['receiver']}\n";
                    $queue->ackLastMessage();
                } else {
                    echo date('d.m.y H:i') . " Ошибка отправки сообщения: ", var_export($result, true), "\n";
                    // Не подтверждаем, чтобы повторить позже
                }
            } else {
                echo "Некорректное сообщение в очереди: $msg\n";
                $queue->ackLastMessage(); 
            }
        }
    }
} 