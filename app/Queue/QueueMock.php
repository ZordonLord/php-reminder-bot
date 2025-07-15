<?php
namespace App\Queue;

class QueueMock implements Queue
{
    private array $messages = [];
    private $lastMessageIndex = null;

    public function sendMessage($message): void
    {
        $this->messages[] = $message;
    }

    public function getMessage(): ?string
    {
        if (empty($this->messages)) {
            $this->lastMessageIndex = null;
            return null;
        }
        $this->lastMessageIndex = 0;
        return $this->messages[0];
    }

    public function ackLastMessage(): void
    {
        if ($this->lastMessageIndex !== null) {
            array_shift($this->messages);
            $this->lastMessageIndex = null;
        }
    }
} 