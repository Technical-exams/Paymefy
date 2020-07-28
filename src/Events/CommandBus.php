<?php namespace Proweb21\Elevator\Events;

final class CommandBus implements Bus
{
    use DispatcherTrait;

    public function name(): string
    {
        return 'elevator.command.bus';
    }
}
