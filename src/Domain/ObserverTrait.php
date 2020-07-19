<?php namespace Proweb21\Elevator\Domain;

/**
 * Trait implementing Observer interface
 * for Domain observer classes
 */
trait ObserverTrait
{

    public function observe(string $observable_event_class, string $method)
    {
        DomainEventPublisher::instance()->attachObserver($this, $method, $observable_event_class);
    }
}