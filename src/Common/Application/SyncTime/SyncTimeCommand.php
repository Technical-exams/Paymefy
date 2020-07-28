<?php namespace Proweb21\Elevator\Application\SyncTime;

use Proweb21\Command;

/**
 * Command requesting to sync time with a clock hour and minute
 * 
 * @property-read int $hour
 * @property-read int $minute
 * 
 * @package Common
 */
final class SyncTimeCommand implements Command
{

    /**
     * The 24h clock hour of new time to be synced
     *
     * @var int
     */
    protected $hour;

    /**
     * The clock minute of new time to be synced
     *
     * @var int
     */
    protected $minute;

    /**
     * Constructor
     * 
     * Creates a new instance of a MinutePassed Event
     *
     * @param int $hour
     * @param int $minute
     * @return void
     */
    public function __construct(int $hour, int $minute)
    {
        $this->minute = $minute;
        $this->hour =$hour;
    }

    public function __get(string $property)
    {
        switch($property){
            case "hour":
                return $this->getHour();
            case "minute":
                return $this->getMinute();
        }
    }

    /**
     * Getter for $hour property
     *
     * @return integer
     */
    public function getHour(): int
    {
        return $this->hour;
    }

    /**
     * Getter for $minute property
     *
     * @return integer
     */
    public function getMinute(): int
    {
        return $this->minute;
    }
    
}