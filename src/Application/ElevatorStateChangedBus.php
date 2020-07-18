<?php namespace Proweb21\Elevator\Application;

use Proweb21\Elevator\Events\EventBus;
use Proweb21\Elevator\Events\EventDispatcherTrait;

class ElevatorStateChangedBus
    implements EventBus
{
    use EventDispatcherTrait;

    public function name(): string
    {
        return 'elevator.state.bus';
    }

}