<?php namespace Proweb21\Elevator\Application;

use Proweb21\Elevator\Events\EventBus;
use Proweb21\Elevator\Events\EventDispatcherTrait;

class ElevatorCallsBus
    implements EventBus
{
    use EventDispatcherTrait;

    public function name(): string
    {
        return 'elevator.calls.bus';
    }

}