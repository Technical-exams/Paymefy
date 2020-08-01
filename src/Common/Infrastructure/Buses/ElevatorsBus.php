<?php namespace Proweb21\Elevators\Common\Infrastructure\Buses;

use Proweb21\Bus;
use Proweb21\CommandBus;

/**
 * Bus intended for elevator commands notification
 *
 * @package Common
 */
final class ElevatorsBus extends CommandBus implements Bus
{
    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function name(): string
    {
        return 'elevators.bus';
    }
}
