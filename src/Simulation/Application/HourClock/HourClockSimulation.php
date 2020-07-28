<?php namespace Proweb21\Elevator\Simulation\Application\Chrono;

use Proweb21\Elevator\Simulation\Application\Simulation;
use Proweb21\Elevator\Simulation\Application\SimulationTrait;
use Proweb21\Elevator\Application\SyncTime\SyncTimeCommand;
use Proweb21\Elevator\Infrastructure\Buses\TimeBus;
use Proweb21\Elevator\Simulation\Model\HourClockTime\HourClockTimeProvider;

/**
 * Simulates the pass of the system time from an start time to and end time
 *
 * @uses HourClockTimeProvider
 * @uses TimeBus
 */
class HourClockSimulation implements Simulation
{
    use SimulationTrait;

    /**
     * Bus where the simulation notifies the system time to sync
     *
     * @var TimeBus
     */
    protected $bus;

    /**
     * Clock used to produce clock events
     *
     * @var HourClockTimeProvider
     */
    protected $clock;


    /**
     * Which hour the simulation must start
     *
     * @var \DateTimeImmutable
     */
    protected $start_time;

    /**
     * Which hour the simulation must end
     *
     * @var \DateTimeImmutable
     */
    protected $end_time;

    /**
     * Creates a Simulation instance
     *
     * @param TimeBus $bus
     * @param \DateTimeImmutable $start_time
     * @param \DateTimeImmutable $end_time
     */
    public function __construct(TimeBus $bus, \DateTimeImmutable $start_time, \DateTimeImmutable $end_time)
    {
        $this->bus = $bus;
        $this->start_time;
        $this->end_time;

        $diff = date_diff($this->end_time, $this->start_time, true);
        // How many minutes must be provided
        $count = $diff->h*60+$diff->i;

        if (is_null($count) or (0>=$count)) {
            throw new \InvalidArgumentException("Start time and End time are not right.");
        }
            
        $this->clock = new HourClockTimeProvider($count);
    }
   

    /**
     * Starts the Hour simulator
     *
     * @param int $hour
     * @return void
     */
    public function doRun()
    {
        $hour = $this->start_time->format('H');
        $minute = $this->start_time->format('i');

        foreach ($this->clock($hour, $minute) as $clock_time) {
            if (!$this->started()) {
                break;
            }
            $command = new SyncTimeCommand($clock_time["hour"], $clock_time["minute"]);
            $this->bus->dispatch($command);
        }
    }
}
