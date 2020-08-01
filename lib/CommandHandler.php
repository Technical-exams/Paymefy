<?php namespace Proweb21;

/**
 * Base class for command handlers
 * 
 * Command Handlers recieve publications (commands) from channels (CommandBuses)
 * @see https://en.wikipedia.org/wiki/Publish%E2%80%93subscribe_pattern
 */
abstract class CommandHandler implements Subscriber
{

    /**
     * {@inheritDoc}
     */
    public function subscribe(Bus $bus)
    {
        if (! ($bus instanceof CommandBus))
            throw new \AssertionError("An CommandHandler can only subscribe to CommandBuses");

        $bus->subscribe($this);
    }

    /**
     * Handles a Command
     *
     * @param [type] $command
     * @return void
     */
    public function handle(Command $command)
    {
        throw new \RuntimeException("Override this method in the descendant classes");
    }
}