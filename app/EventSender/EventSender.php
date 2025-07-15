<?php
namespace App\EventSender;

use App\Application;
use App\Queue\Queue;
use App\Queue\Queueable;

class EventSender implements Queueable
{
    private TelegramApi $telegramApi;
    private Queue $queue;

    public function __construct(TelegramApi $telegramApi, Queue $queue)
    {
        $this->telegramApi = $telegramApi;
        $this->queue = $queue;
    }

    public function toQueue(...$args): void
    {
        // args: receiver, message
        $this->queue->sendMessage(json_encode([
            'receiver' => $args[0],
            'message' => $args[1]
        ]));
        echo date('d.m.y H:i') . " Сообщение поставлено в очередь для {$args[0]}\n";
    }

    public function handle(): void
    {
        $msg = $this->queue->getMessage();
        if ($msg === null) {
            echo "Очередь пуста\n";
            return;
        }
        $data = json_decode($msg, true);
        if (isset($data['receiver'], $data['message'])) {
            $result = $this->telegramApi->sendMessage($data['receiver'], $data['message']);
            if ($result && isset($result['ok']) && $result['ok']) {
                echo date('d.m.y H:i') . " Сообщение '{$data['message']}' отправлено получателю с id {$data['receiver']}\n";
                $this->queue->ackLastMessage();
            } else {
                echo date('d.m.y H:i') . " Ошибка отправки сообщения: ", var_export($result, true), "\n";
            }
        } else {
            echo "Некорректное сообщение в очереди: $msg\n";
            $this->queue->ackLastMessage();
        }
    }
}