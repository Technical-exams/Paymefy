<?php namespace Proweb21\Elevator\Events;

/**
 * Interface for implementations of helper classes notifying Observer of an Observable update 
 * These helper classes will be called directly from those Observable classes
 * @see https://en.wikipedia.org/wiki/Observer_pattern
 */
interface ObservableEventSubject
{

    /**
     * Attaches an observer observing (or listening) a kind of observable events
     *
     * @param EventObserver $observer An observer of observable events
     * @param string $method Which method may be called in the observer
     * @param string $observable_event_class
     * @return void
     */
    public function attachObserver(EventObserver $observer, string $method, string $observable_event_class);

    /**
     * Notifies an ObservableEvent to all observers
     *
     * @param ObservableEvent $event
     * @return void
     */
    public function updateObservers(ObservableEvent $event);
}