<?php namespace Proweb21\Elevator\Events;

/**
 * Event occurred
 */
interface Event
{
    /**
     * Gets the moment in time when the event occurred
     *
     * @return \DateTimeImmutable
     */
    public function getTime(): \DateTimeImmutable;
}