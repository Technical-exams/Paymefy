<?php namespace Proweb21\Elevator\Domain;

/**
 * Singleton class implementing the Observers Update notification system 
 * in the Observer pattern
 * @see https://en.wikipedia.org/wiki/Observer_pattern
 */
final class DomainEventPublisher    
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
     * @var DomainEventPublisher
     */
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
     * Attaches an observer (akd Listener) to receive notifications
     * of DomainEvents occurred in DomainSubjects
     *
     * @param Observer $observer The observer to attach
     * @param string $domain_subject The FQDN of the DomainSubject
     *      
     * @return void
     */
    public function attachObserver(DomainEventObserver $observer, string $domain_subject)
    {
        if (!array_key_exists($domain_subject, $this->observers))
            $this->observers[$domain_subject] = [];
        $this->observers[$domain_subject][] = $observer;
    }

    /**
     * Notifies an DomainEvent to all observers of an DomainSubject
     *
     * @param DomainEvent $event
     * @return void
     */    
    public function updateObservers(DomainSubject $subject, DomainEvent $event)
    {
        $class=get_class($subject);
        if (!array_key_exists($class,$this->observers))
            return;
        foreach ($this->observers[$class] as $observer){
            $events_observed = $observer->getObservedEvents();
            if (array_key_exists(get_class($event),$events_observed)){                
                $observer->update($subject,$event);
            }
        }            
    }

    
}