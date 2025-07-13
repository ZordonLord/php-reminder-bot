<?php
namespace App\EventSender;

class TelegramApi
{
    private string $token;
    private string $apiUrl;
    private int $offset = 0;

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

    /**
     * Получить новые сообщения из Telegram.
     * @param int|null $offset
     * @param int $timeout
     * @return array|null
     */
    public function getMessages(?int $offset = null, int $timeout = 1): ?array
    {
        if ($offset !== null) {
            $this->offset = $offset;
        }
        $params = [
            'timeout' => $timeout,
            'offset' => $this->offset > 0 ? $this->offset : null,
        ];
        $params = array_filter($params, fn($v) => $v !== null);
        $url = $this->apiUrl . 'getUpdates?' . http_build_query($params);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        if ($response === false) {
            curl_close($ch);
            return null;
        }
        curl_close($ch);
        $result = json_decode($response, true);
        // Обновляем offset, если есть новые сообщения
        if (isset($result['result']) && is_array($result['result']) && count($result['result']) > 0) {
            $lastUpdate = end($result['result']);
            if (isset($lastUpdate['update_id'])) {
                $this->offset = $lastUpdate['update_id'] + 1;
            }
        }
        return $result;
    }
} 