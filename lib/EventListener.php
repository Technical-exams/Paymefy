<?php namespace Proweb21;

/**
 * Base class for event listeners
 * 
 * Event Listener recieve notifications (events) from channels (EventBuses)
 * @see https://en.wikipedia.org/wiki/Publish%E2%80%93subscribe_pattern
 */
abstract class EventListener implements Subscriber
{

    /**
     * {@inheritDoc}
     */
    public function subscribe(Bus $bus)
    {
        if (! ($bus instanceof EventBus))
            throw new \AssertionError("An EventSubscriber can only subscribe to EventBuses");

        $bus->subscribe($this);
    }

    public function handle($object)
    {
        throw new \RuntimeException("Override this method in the descendant classes");
    }

}