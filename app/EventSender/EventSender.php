<?php
namespace App\EventSender;

use App\Application;

class EventSender
{
    private TelegramApi $telegramApi;

    public function __construct(Application $app)
    {
        $token = $app->env('TELEGRAM_BOT_TOKEN');
        $this->telegramApi = new TelegramApi($token);
    }

    public function sendMessage(string $receiver, string $message): void
    {
        $result = $this->telegramApi->sendMessage($receiver, $message);
        if ($result && isset($result['ok']) && $result['ok']) {
            echo date('d.m.y H:i') . " Сообщение '$message' отправлено получателю с id $receiver\n";
        } else {
            echo date('d.m.y H:i') . " Ошибка отправки сообщения: ", var_export($result, true), "\n";
        }
    }
}