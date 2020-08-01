<?php namespace Proweb21\Elevators\Common\Infrastructure\Buses;

use Proweb21\Bus;
use Proweb21\EventBus;

/**
 * Bus intended for elevator commands notification
 *
 * @package Common
 */
final class MovesBus extends EventBus implements Bus
{
    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function name(): string
    {
        return 'moves.bus';
    }
}
