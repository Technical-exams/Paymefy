<?php namespace Proweb21;

/**
 * Interface for publishers
 * 
 * Publishers broadcast publications (dispatchable objects) through channels (Buses)
 * @see https://en.wikipedia.org/wiki/Publish%E2%80%93subscribe_pattern
 */
interface Publisher
{
    /**
     * Broadcasts a Dispatchable Object to an Bus
     *
     * @param Dispatchable $object
     * @param Bus $bus
     * @return void
     */
    public function publish($object, $bus);

}