<?php namespace Proweb21\Elevator\Application\Simulators\ElevatorCalls;

use Proweb21\Elevator\Application\ElevatorCalls\ElevatorCallFactory;
use Proweb21\Elevator\Application\ElevatorCalls\ElevatorCallsBus;
use Proweb21\Elevator\Application\Simulators\Simulator;
use Proweb21\Elevator\Application\Simulators\SimulatorTrait;
use Proweb21\Elevator\Events\EventHandler;
use Proweb21\Elevator\Events\EventPublisher;
use Proweb21\Elevator\Events\EventPublisherTrait;
use Proweb21\Elevator\Events\Time\MinutePassed;

/**
 * Simulates user calls to elevators
 */
class ElevatorCallsSimulator
    implements EventHandler,
               EventPublisher,
               Simulator
{

    use EventPublisherTrait;

    use SimulatorTrait;

    /**
     * Bus for notifying any elevator call occurred
     *
     * @var ElevatorCallsBus
     */
    protected $bus;


    /**
     * The offset applied to the frequency
     *
     * @var int
     */
    protected $offset;


    /**
     * How often an elevator call is created
     *
     * @var int
     */
    protected $frequency;

    /**
     * Factory for Elevator Calls creation
     * 
     * @var ElevatorCallFactory
     */
    protected $factory;

    /**
     * Simulator constructor
     *
     * @param ElevatorCallsBus $bus Where to dispatch events
     * @param ElevatorCallFactory $factory Factory for creation of ElevatorCalled Events
     * @param integer $frequency How many often (minutes) ElevatorCalled must be produced
     */
    public function __construct(ElevatorCallsBus $bus, ElevatorCallFactory $factory, int $frequency)
    {
        if ($frequency < 1 || $frequency > 59)
            throw new \InvalidArgumentException("BAD FREQUENCY FOR ELEVADOR CALL SIMULATOR, GIVEN ${frequency}");
        $this->offset = random_int(0,$frequency-1);
        $this->frequency = $frequency;
        $this->bus = $bus;
        $this->factory = $factory;
    }

    /**
     * Handles MinutePassed events
     *
     * If a minute matches the current frecuency then an elevator is called
     * 
     * @param MinutePassed $event
     * @return void
     */
    public function handle(MinutePassed $event){

        $minute = (int)($event->time->format('i'));
        if ((($minute + $this->offset) % $this->frequency) == 0) {
            if ($this->started()) {
                $elevator_called = $this->factory->create($event->time);
                $this->publish($elevator_called,$this->bus);
            }
        }
    }

    
}