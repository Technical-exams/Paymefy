<?php namespace Proweb21;

/**
 * Event occurred
 */
interface Event extends Dispatchable
{
    /**
     * Gets the moment in time when the event occurred
     *
     * @return \DateTimeImmutable
     */
    public function getTime(): \DateTimeImmutable;
}
