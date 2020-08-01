<?php namespace Proweb21;

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
     * @param Observer $observer An observer of observable events
     * @param string $observable class name of an observable
     * @return void
     */
    public function attachObserver($observer, string $observable);

    /**
     * Notifies an ObservableEvent ocurred on Observable subject to all observers
     *
     * @param ObservableEvent $event
     * @return void
     */
    public function updateObservers($event);
}
