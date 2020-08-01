<?php namespace Proweb21;

interface Dispatcher
{
    /**
     * Subscribes a subscriber to message broadcasting
     *
     * @param Subscriber $subscriber
     * @return void
     */
    public function subscribe($subscriber);


    /**
     * Frees dispatcher from subscribers
     *
     * @return void
     */
    public function clearSubscribers();

    /**
     * Dispatch a dispatchable message $object to subscribers
     *
     * @param Dispatchable $object
     * @return void
     */
    public function dispatch($object);
}
