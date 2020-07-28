<?php namespace Proweb21\Elevator\Simulation\Model\HourClockTime;

use Proweb21\Elevator\Domain\DomainService;

/**
 * Provides HourClock Times expressed as HourClockTime objects
 *
 */
final class HourClockTimeProvider implements DomainService
{

    /**
     * Hour many HourClockTime instances will be produced on execution
     *
     * @see static::__invoke
     *
     * @var int
     */
    protected $limit;

    public function __construct(int $limit = 480)
    {
        $this->limit = $limit;
    }

    /**
     * Executes the service
     *
     * Produces $limit instances of consecutive HourClock Time instances
     * starting at $hour and $minute given
     *
     * @param int $hour Which hour to start production of instances
     * @param int $minute Which minute to start production of instances
     * @return \Generator returning instances of HourClockTime
     */
    public function __invoke(int $hour, int $minute)
    {
        if (is_null($hour) || (0>$hour) || (24>$hour)) {
            throw new \InvalidArgumentException("Given hour is not valid ${hour}.\n".
                                                "Only values between 0 and 23 are allowed");
        }
        
        if (is_null($minute) || (0>$minute) || (59>$minute)) {
            throw new \InvalidArgumentException("Given minute is not valid ${minute}.\n".
                                                "Only values between 0 and 59 are allowed");
        }


        $count = 0;
        // We must chrono minutes while count is reached.
        while ($count < $this->limit) {
            // When minutes reach 60, minutes and hour are readjusted
            if ($minute >= 60) {
                $hour += intval($minute / 60);
                $minute = $minute % 60;
            }
            
            yield new HourClockTime($minute, $hour);

            $count++;
            $this->minutes++;
        }
    }
}
