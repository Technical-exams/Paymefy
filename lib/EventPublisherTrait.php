<?php namespace Proweb21;

/**
 * Trait implementing the EventPublisher interface
 */
trait EventPublisherTrait
{
    /**
     * {@inheritDoc}
     */
    public function publish($event,$bus)
    {
        //PRECONDITION
        if ( ! ($event instanceof Event) )
            throw new \InvalidArgumentException("Cannot publish what is not an Event");        
        if ( ! ($bus instanceof EventBus) )
            throw new \InvalidArgumentException("Cannot publish through a bus what is not an EventBus");

        $bus->dispatch($event);
    }
}