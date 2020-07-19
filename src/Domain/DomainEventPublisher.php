<?php namespace Proweb21\Elevator\Domain;

use Proweb21\Elevator\Events\Observer;
use Proweb21\Elevator\Events\ObservableEvent;
use Proweb21\Elevator\Events\ObservableEventSubject;

/**
 * Singleton class implementing the Observers Update notification system 
 * in the Observer pattern
 * @see https://en.wikipedia.org/wiki/Observer_pattern
 */
class DomainEventPublisher
    implements ObservableEventSubject
{
    private $observers;

    protected static $instance;

    /**
     * Singleton class instance accessor
     */
    public static function instance() : DomainEventPublisher
    {
        self::$instance = self::$instance ? : new static();
        return self::$instance;
    }

    /**
     * Protected constructor
     * to protect the singleton
     */
    protected function __construct() {
        $this->observers = [];
    }

    /**
     * {@inheritDoc}
     */
    public function attachObserver(Observer $observer, string $method, string $observable_event_class)
    {
        if (!array_key_exists($observable_event_class, $this->observers))
            $this->observers[$observable_event_class] = [];
        $this->observers[$observable_event_class][] = [$observer,$method];
    }

    /**
     * {@inheritDoc}
     */
    public function updateObservers(ObservableEvent $event)
    {
        $class=get_class($event);
        if (!array_key_exists($class,$this->observers))
            return;
        foreach ($this->observers[$class] as $observer_method)
            call_user_func($observer_method, $event);
    }

    
}