<?php namespace Proweb21;

final class CommandBus implements Bus
{
    use DispatcherTrait;

    public function name(): string
    {
        return 'elevator.command.bus';
    }
}
