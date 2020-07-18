<?php namespace Proweb21\Elevator\Domain;

use Proweb21\Elevator\Events\ObservableEvent;

/**
 * Trait implementing Observable interface
 * for Domain observable classes
 */
trait ObservableTrait
{

    public function publish(ObservableEvent $event)
    {
        DomainEventPublisher::instance()->updateObservers($event);
    }
}