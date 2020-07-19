<?php namespace Proweb21\Elevator\Events;

/**
 * Interface for implementations of the observer in the Observer pattern
 * @see https://en.wikipedia.org/wiki/Observer_pattern
 */
interface Observer
{
    /**
     * Manages an event the instance is observing
     *
     * @param ObservableEventSubject $events
     * @return void
     */
    public function observe(ObservableEventSubject $events);

}