<?php namespace Proweb21\Elevator\Application\Simulators\Time;

use Proweb21\Elevator\Application\Simulators\Simulator;
use Proweb21\Elevator\Application\Simulators\SimulatorTrait;
use Proweb21\Elevator\Events\Time\MinutePassed;
use Proweb21\Elevator\Events\Time\TimeBus;

/**
 * Simulates the pass of a given time expressed in minutes
 * starting from an hour of the day
 * 
 * @uses Chrono
 * @uses TimeBus
 */
class ChronoSimulator
    implements Simulator
{

    use SimulatorTrait;
    /**
     * Bus where the clock events are notified
     *
     * @var TimeBus
     */
    protected $bus;

    /**
     * Clock used to produce clock events
     *
     * @var HourClock
     */
    protected $clock;

    public function __construct(TimeBus $bus, int $count=null)
    {
        $this->bus = $bus;
        if (!is_null($count))
            $this->clock = new Chrono($count);
        else
            $this->clock = new Chrono();
    }

    /**
     * Resets the ChronoSimulator to given time
     * expressed as an hour of the day
     *
     * @param integer $hour
     * @param integer $minutes
     * @return void
     */
    public function setup(int $hour = null, int $minutes = null)
    {
        if ($this->started())
            throw new \RuntimeException("ChronoSimulator is started, cannot be set up");

        if (!is_null($hour) && (0<=$hour) && (23>=$hour))
            if (!is_null($minutes) && (0<=$minutes) && (59>=$minutes))
                $this->clock->reset($hour,$minutes);
            else
                $this->clock->reset($hour);
    }

    /**
     * Starts the Hour simulator
     *
     * @param int $hour
     * @return void
     */
    public function doRun()
    {
        foreach($this->clock->minutes() as $clock_time){            
            if (!$this->started()) break;
            $event = new MinutePassed($clock_time["hour"],$clock_time["minute"]);
            $this->bus->dispatch($event);
        }
    }

}