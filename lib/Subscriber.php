<?php namespace Proweb21;

/**
 * Interface for subscribers
 * 
 * Event Subscribers recieve publications (dispatchable objects) from channels (Buses)
 * @see https://en.wikipedia.org/wiki/Publish%E2%80%93subscribe_pattern
 */
interface Subscriber
{
    /**
     * Subscribes itself to an Bus
     *
     * @param Bus $bus
     * @return void
     */
    public function subscribe(Bus $bus);
}