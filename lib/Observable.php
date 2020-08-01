<?php namespace Proweb21;

/**
 * Interface for classes whose are observed
 * @see https://en.wikipedia.org/wiki/Observer_pattern
 */
interface Observable
{
  
    /**
     * Publish an event notifying any observers
     *
     * @param ObservableEvent $event
     * @return void
     */
    public function notify($event);
}