<?php namespace Proweb21\Elevator\Model\SystemTime;

use Proweb21\Elevator\Domain\DomainService;

/**
 * Provider for accessing SystemTime
 * 
 */
final class SystemTimeProvider implements DomainService
{
    protected $system_time;

   /**
     * Gets the system time singleton entity
     */
    public function __invoke() : \DateTimeImmutable
    {
        self::$system_time = self::$system_time ? : new SystemTime();
        return self::$system_time->getTime();
    }

    /**
     * Synces system time with given time
     *
     * @param \DateTimeImmutable $time
     * @return void
     */
    public function sync(\DateTimeImmutable $time)
    {        
        if (!is_null(self::$system_time)) {
            $gap = intval(abs($time->getTimestamp() - self::$system_time->getTime()->getTimestamp()));
            if ($gap > 59) {
                // REPORT THE GAP
            }
        }        
        self::$system_time = new SystemTime($time);
        // Can emit a notification of SystemTimeSynced event via DomainEvents Bus
    }

}