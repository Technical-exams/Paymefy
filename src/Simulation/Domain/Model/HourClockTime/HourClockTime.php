<?php namespace Proweb21\Elevators\Simulation\Model\HourClockTime;

/**
 * ValueObject representing the time in an HourClock
 * 
 * @property-read int $hour
 * @property-read int $minutes
 */
final class HourClockTime
{
    /**
     * Time's Hour in a Clock
     *
     * @var int
     */
    protected $hour;

    /**
     * Time's minutes in a Clock
     *
     * @var int
     */
    protected $minutes;


    /**
     * Constructor
     * 
     * Creates a new instance of HourClockTime
     *
     * @param integer $minutes
     * @param integer $hour
     */
    public function __construct(int $minutes = 0, int $hour = 0)
    {
        $this->minutes = $minutes;
        $this->hour = $hour;
    }

    /**
     * Read-only properties accessor
     *
     * @param string $property
     * @return void
     */
    public function __get(string $property)
    {
        switch($property){
            case "minutes":
                return $this->getMinutes();
            case "hour":
                return $this->getHour();
        }
    }

    /**
     * Getter for $hour
     *
     * @return integer
     */
    public function getHour():int
    {
        return $this->hour;
    }

    /**
     * Getter for $minutes
     *
     * @return integer
     */
    public function getMinutes(): int
    {
        return $this->minutes;
    }

}