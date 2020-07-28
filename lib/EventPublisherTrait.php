<?php namespace Proweb21;

/**
 * Trait implementing the EventPublisher interface
 */
trait EventPublisherTrait
{
    /**
     * {@inheritDoc}
     */
    public function publish(Event $event, EventBus $bus)
    {
        $bus->dispatch($event);
    }
}