<?php namespace Proweb21\Elevator\Events;

use Proweb21\Elevator\Events\EventBus;
use Proweb21\Elevator\Events\EventDispatcherTrait;

final class CommandBus implements EventBus
{
    use EventDispatcherTrait;

    public function name(): string
    {
        return 'elevator.command.bus';
    }
}
