<?php namespace Proweb21;

/**
 * Interface for event publishers
 * 
 * Event Publishers broadcasts events through channels (EventBuses)
 * @see https://en.wikipedia.org/wiki/Publish%E2%80%93subscribe_pattern
 */
interface EventPublisher
{
    /**
     * Broadcasts an Event to an EventBus
     *
     * @param Event $event
     * @param EventBus $bus
     * @return void
     */
    public function publish(Event $event, EventBus $bus);
}