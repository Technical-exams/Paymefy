<?php namespace Proweb21;

/**
 * Interface for event publishers
 * 
 * Event Publishers broadcasts events through channels (EventBuses)
 * @see https://en.wikipedia.org/wiki/Publish%E2%80%93subscribe_pattern
 */
interface EventPublisher extends Publisher
{
    /**
     * Broadcasts an Event to an EventBus
     *
     * @param Event $event
     * @param EventBus $bus
     * @return void
     * 
     * @throws \InvalidArgumentException when $event is not an instance of Event 
     *                                   or when $bus is not an instance of EventBus
     */
    public function publish($event, $bus);
}