<?php namespace Proweb21\Elevator\Infrastructure\Buses;

use Proweb21\Bus;
use Proweb21\CommandBus;

/**
 * Bus intended for time events notification
 *
 * @package Common
 */
final class TimeBus extends CommandBus implements Bus
{
    public function name(): string
    {
        return 'time.bus';
    }
}
