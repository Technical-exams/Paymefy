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
     * @param string $observable_class
     * @param string $method
     * @return void
     */
    public function observe(string $observable_class);

    /**
     * Tells which events are observed and which method to call
     * for each one of them
     *
     * @return ObservableEvent::class[]
     */
    public function getObservedEvents() : array;

    /**
     * Updates the Observer with an event notification from Observable
     *
     * @param Observable $subject
     * @param ObservableEvent $event
     * @return void
     */
    public function update($subject, $event);
}