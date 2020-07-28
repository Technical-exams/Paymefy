<?php namespace Proweb21\Elevator\Domain;

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
        DomainEventPublisher::instance()->attachObserver($this, $observable_class);
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
