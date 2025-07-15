<?php
namespace App\Queue;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQQueue implements Queue
{
    private $connection;
    private $channel;
    private $queueName;
    private $lastDeliveryTag;

    public function __construct($host, $port, $user, $password, $queueName)
    {
        $this->connection = new AMQPStreamConnection($host, $port, $user, $password);
        $this->channel = $this->connection->channel();
        $this->queueName = $queueName;
        $this->channel->queue_declare($queueName, false, true, false, false);
    }

    public function sendMessage($message): void
    {
        $msg = new AMQPMessage($message, ['delivery_mode' => 2]); // persistent
        $this->channel->basic_publish($msg, '', $this->queueName);
    }

    public function getMessage(): ?string
    {
        $msg = $this->channel->basic_get($this->queueName);
        if ($msg) {
            $this->lastDeliveryTag = $msg->delivery_info['delivery_tag'];
            return $msg->body;
        }
        return null;
    }

    public function ackLastMessage(): void
    {
        if ($this->lastDeliveryTag) {
            $this->channel->basic_ack($this->lastDeliveryTag);
            $this->lastDeliveryTag = null;
        }
    }

    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }
} 