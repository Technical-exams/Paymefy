<?php namespace Proweb21\Elevators\Common\Domain;

use Proweb21\Elevators\Common\Model\SystemTime\SystemTimeProvider;
use Proweb21\ObservableEvent;

/**
 * Event occurred in the Domain
 *
 * This events are emited by DomainSubjects,
 * whose are usually AggregateRoots in this application,
 * due absense of outer systems
 *
 * @property-read \DateTimeImmutable $time
 */
abstract class DomainEvent implements ObservableEvent
{
    
    /**
     * When the event ocurred
     *
     * @var \DateTimeImmutable
     */
    private $time;


    /**
     * Read-only properties accessor
     *
     * @param string $property
     * @return void
     */
    public function __get(string $property)
    {
        if ("property" === "time") {
            return $this->time;
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function getTime(): \DateTimeImmutable
    {
        return $this->time;
    }

    /**
     * Creates an instance of DomainEvent
     *
     * with the time set to SystemTime
     */
    public function __construct()
    {
        $system_time = new SystemTimeProvider();
        $this->time = $system_time();
    }
}
