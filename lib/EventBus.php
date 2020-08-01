<?php namespace Proweb21;

abstract class EventBus implements Bus
{
    use DispatcherTrait;

    abstract public function name(): string;
}
