<?php
namespace App\EventSender;

class TelegramApi
{
    private string $token;
    private string $apiUrl;

    public function __construct(string $token)
    {
        $this->token = $token;
        $this->apiUrl = "https://api.telegram.org/bot{$this->token}/";
    }

    public function sendMessage(string $chatId, string $text): ?array
    {
        $url = $this->apiUrl . 'sendMessage';
        $postFields = [
            'chat_id' => $chatId,
            'text' => $text,
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        if ($response === false) {
            curl_close($ch);
            return null;
        }
        curl_close($ch);
        return json_decode($response, true);
    }

    public function getMessages(array $params = []): ?array
    {
        $url = $this->apiUrl . 'getUpdates';
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        if ($response === false) {
            curl_close($ch);
            return null;
        }
        curl_close($ch);
        return json_decode($response, true);
    }
} 