<?php namespace Proweb21\Elevator\Events;

interface Dispatcher
{
    public function subscribe(string $dispatchable_class, Handler $handler);

    public function clearSubscribers();

    public function dispatch(Dispatchable $object);
}
