<?php
namespace App\Actions;

use Psr\SimpleCache\CacheInterface;

class TgEvents
{
    private int $offset;
    private array $oldMessages;
    private CacheInterface $cache;

    public function __construct(
        Cron $cron,
        EventSaver $eventSaver,
        TelegramApi $telegramApi,
        EventSender $eventSender,
        CacheInterface $cache
    ) {
        $this->cache = $cache;
        $this->offset = $this->cache->get('tg_offset', 0);
        $this->oldMessages = $this->cache->get('tg_old_messages', []);
    }

    public function setOffset(int $offset): void
    {
        $this->offset = $offset;
        $this->cache->set('tg_offset', $offset);
    }

    public function setOldMessages(array $messages): void
    {
        $this->oldMessages = $messages;
        $this->cache->set('tg_old_messages', $messages);
    }
    // ... existing code ...
} 