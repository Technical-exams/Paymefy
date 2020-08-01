<?php namespace Proweb21\Elevators\Common\Domain;

use Proweb21\Observer;

/**
 * Base class for Domain Event Observer Services
 *
 */
abstract class DomainEventObserver implements Observer
{
    /**
     * {@inheritDoc}
     *
     */
    public function observe(string $observable_class)
    {
        DomainEvents::instance()->attachObserver($this, $observable_class);
    }

    /**
     * {@inheritDoc}
     *
     */
    abstract public function getObservedEvents() : array;

    /**
     * {@inheritDoc}
     *
     */
    abstract public function update($subject, $event);
}
