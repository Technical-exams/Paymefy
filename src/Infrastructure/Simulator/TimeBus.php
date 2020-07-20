<?php namespace Proweb21\Elevator\Infrastructure\Simulator;

use Proweb21\Elevator\Events\EventBus;
use Proweb21\Elevator\Events\EventDispatcherTrait as EventsEventDispatcherTrait;

/**
 * Bus intended for time events notification
 */
final class TimeBus
    implements EventBus
{
    use EventsEventDispatcherTrait;

    public function name(): string
    {
        return 'time.bus';
    }

}