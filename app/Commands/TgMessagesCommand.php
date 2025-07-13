<?php

namespace App\Commands;

use App\Application;
use App\EventSender\TelegramApi;

class TgMessagesCommand extends Command
{
    protected Application $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function run(array $options = []): void
    {
        $token = $this->app->env('TELEGRAM_BOT_TOKEN');
        $api = new TelegramApi($token);
        $result = $api->getMessages();
        if ($result && isset($result['ok']) && $result['ok']) {
            foreach ($result['result'] as $update) {
                $msg = $update['message'] ?? null;
                if ($msg) {
                    $from = $msg['from']['username'] ?? $msg['from']['id'] ?? 'unknown';
                    $text = $msg['text'] ?? '';
                    echo "[{$from}] {$text}\n";
                }
            }
        } else {
            echo "Ошибка получения сообщений: ", var_export($result, true), "\n";
        }
    }
} 