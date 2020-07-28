<?php namespace Proweb21\Elevator\Infrastructure\Buses;

use Proweb21\Bus;
use Proweb21\CommandBus;
use Proweb21\DispatcherTrait;

/**
 * Bus intended for time events notification
 * 
 * @package Common
 */
final class TimeBus extends CommandBus implements Bus
{
    use DispatcherTrait;

    public function name(): string
    {
        return 'time.bus';
    }
}
