<?php namespace Proweb21\Elevator\Events;

interface EventDispatcher
{
    public function subscribe(string $event_class, EventHandler $handler);

    public function clearSubscribers();

    public function dispatch(Event $event);
}