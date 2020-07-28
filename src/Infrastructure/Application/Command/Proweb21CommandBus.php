<?php namespace Proweb21\Elevator\Infrastructure\Application\Command;

use Proweb21\Bus;
use Proweb21\CommandBus;

final class Proweb21CommandBus extends CommandBus implements Bus
{
    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function name(): string
    {
        return 'elevator.command.bus';
    }
}
