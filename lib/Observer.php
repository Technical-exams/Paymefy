<?php namespace Proweb21;

/**
 * Interface for implementations of the observer in the Observer pattern
 * @see https://en.wikipedia.org/wiki/Observer_pattern
 */
interface Observer
{
    /**
     * Manages an event the instance is observing
     *
     * @param string $observable_event_class
     * @param string $method
     * @return void
     */
    public function observe(string $observable_event_class, string $method);

}