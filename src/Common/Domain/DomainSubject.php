<?php namespace Proweb21\Elevators\Common\Domain;

use Proweb21\Observable;

/**
 * Es una classe abstarcta observable que usa el DomainEvents per a publicar
 */
abstract class DomainSubject implements Observable
{

    /**
     * {@inheritDoc}
     *
     * @param DomainEvent $event
     * @throws AssertionError
     */
    public function notify($event)
    {
        if (! ($event instanceof DomainEvent)) {
            throw new \AssertionError("Cannot publish an event which is not a DomainEvent");
        }

        DomainEvents::instance()->updateObservers($this, $event);
    }
}
