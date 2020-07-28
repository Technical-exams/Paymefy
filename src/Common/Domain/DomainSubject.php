<?php namespace Proweb21\Elevator\Domain;

use Proweb21\Observable;

/**
 * Es una classe abstarcta observable que usa el DomainEventPublisher per a publicar
 */
abstract class DomainSubject implements Observable
{

    /**
     * {@inheritDoc}
     *
     * @param DomainEvent $event
     * @throws AssertionError
     */
    public function publish($event)
    {
        if (! ($event instanceof DomainEvent)) {
            throw new \AssertionError("Cannot publish an event which is not a DomainEvent");
        }

        DomainEventPublisher::instance()->updateObservers($this, $event);
    }
}
