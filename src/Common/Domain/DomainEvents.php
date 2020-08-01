<?php namespace Proweb21\Elevators\Common\Domain;

use Proweb21\Observable;
use Proweb21\ObservableEvent;
use Proweb21\ObservableEventSubject;
use Proweb21\Observer;

/**
 * Singleton class implementing the Observers Update notification system
 * in the Observer pattern
 * @see https://en.wikipedia.org/wiki/Observer_pattern
 */
final class DomainEvents implements ObservableEventSubject
{
    /**
     * The domain event observers
     *
     * @var DomainEventObserver[]
     */
    private $observers;

    /**
     * The singleton instance of this class
     *
     * @var DomainEvents
     */
    protected static $instance;

    /**
     * Singleton class instance accessor
     */
    public static function instance() : DomainEvents
    {
        self::$instance = self::$instance ? : new static();
        return self::$instance;
    }

    /**
     * Protected constructor
     * to protect the singleton
     */
    protected function __construct()
    {
        $this->observers = [];
    }

    /**
     * Attaches an observer (akd Listener) to receive notifications
     * of DomainEvents occurred in DomainSubjects
     *
     * @param DomainEventObserver $observer The observer to attach
     * @param string $domain_subject The FQDN of the DomainSubject
     *
     * @return void
     * 
     * @throws \AssertionError when given observer is not an instance of DomainEventObserver
     */        
    public function attachObserver($observer, string $domain_subject)
    {
        // PRECONDITION
        if ( !($observer instanceof DomainEventObserver) )
            throw new \AssertionError("Observer is not a DomainEventObserver");

        if (!array_key_exists($domain_subject, $this->observers)) {
            $this->observers[$domain_subject] = [];
        }
        $this->observers[$domain_subject][] = $observer;
    }

    /**
     * Notifies an DomainEvent to all observers of an DomainSubject
     *
     * @param DomainEvent $event
     * @return void
     * 
     * @throws \AssertionError When $subject is not a DomainSubject instance
     *                         or when $event is not a DomainEvent instance
     */
    public function updateObservers($event)
    {
        // PRECONDITION
        if ( !($event instanceof DomainEvent))
            throw new \AssertionError("Cannot update observers of an event which is not an DomainEvent");

        $subject = $event->getSubject();

        if ( !($subject instanceof DomainSubject))
        throw new \AssertionError("Cannot update observers of an object which is not a DomainSubject");

        $class=get_class($subject);
        if (!array_key_exists($class, $this->observers)) {
            return;
        }
        foreach ($this->observers[$class] as $observer) {
            $events_observed = $observer->getObservedEvents();
            if (array_key_exists(get_class($event), $events_observed)) {
                $observer->update($subject, $event);
            }
        }
    }
}
