<?php
namespace App\EventSender;

class TelegramApi
{
    private string $token;
    private string $apiUrl;

    public function __construct(string $token)
    {
        $this->token = $token;
        $this->apiUrl = "https://api.telegram.org/bot{$token}/";
    }

    public function sendMessage(string $receiver, string $message): array
    {
        $url = $this->apiUrl . 'sendMessage';
        $params = [
            'chat_id' => $receiver,
            'text' => $message
        ];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result, true);
    }

    public function getMessages(): array
    {
        $url = $this->apiUrl . 'getUpdates';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result, true);
    }
} 