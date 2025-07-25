<?php

namespace App\Commands;

use App\Application;

use App\Database\SQLite;

use App\EventSender\EventSender;

use App\EventSender\TelegramApi;

use App\Models\Event;


use App\Queue\QueueMock;

class HandleEventsCommand extends Command

{

    protected Application $app;

    public function __construct(Application $app)

    {

        $this->app = $app;

    }

    public function run(array $options = []): void

    {

        $event = new Event(new SQLite($this->app));

        $events = $event->select();

        $telegramApi = new TelegramApi($this->app->env('TELEGRAM_BOT_TOKEN'));
        $queue = new QueueMock();
        $eventSender = new EventSender($telegramApi, $queue);

        foreach ($events as $event) {

            if ($this->shouldEventBeRan($event)) {

                $eventSender->sendMessage($event->receiverId, $event->text);

            }

        }

    }

    private function shouldEventBeRan(array $event): bool

    {
        $currentMinute = date("i");

        $currentHour = date("H");

        $currentDay = date("d");

        $currentMonth = date("m");

        $currentWeekday = date("w");

        return ($event['minute'] === $currentMinute &&

            $event['hour'] === $currentHour &&

            $event['day'] === $currentDay &&

            $event['month'] === $currentMonth &&

            $event['weekDay'] === $currentWeekday);
    }

}