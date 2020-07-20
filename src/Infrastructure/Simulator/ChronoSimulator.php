<?php namespace Proweb21\Elevator\Infrastructure\Simulator;

use Proweb21\Elevator\Events\MinutePassed;

/**
 * Simulates the pass of a given time expressed in minutes
 * starting from an hour of the day
 * 
 * @uses Chrono
 * @uses TimeBus
 */
class ChronoSimulator
{
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
        $this->clock = new Chrono($count);
    }

    /**
     * Starts the Hour simulator
     *
     * @param int $hour
     * @return void
     */
    public function start(int $hour = null)
    {
        if (!is_null($hour))
            $this->clock->reset($hour);     
        foreach($this->clock->minutes() as $clock_time){            
            $event = new MinutePassed($clock_time["hour"],$clock_time["minute"]);
            $this->bus->dispatch($event);
            //print(implode(':',$clock_time)."\n");
        }
    }

}