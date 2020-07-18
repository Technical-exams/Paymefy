<?php namespace Proweb21\Elevator\Application;

use Proweb21\Elevator\Events\EventHandler;
use Proweb21\Elevator\Model\Building;
use Proweb21\Elevator\Model\BuildingElevatorsStateFactory as StateFactory;
use Proweb21\Elevator\Model\BuildingElevatorsState;


/**
 * The handler for attending the ElevatorCalled command events
 * 
 */
class ElevatorCalledService
    implements EventHandler
{

    /**
     * Strategy for resolving elevator calls
     *
     * @var BuildingElevatorStrategy
     */
    protected $strategy;

    /**
     * The building with elevators
     *
     * @var Building
     */
    protected $building;

    /**
     * The Building Elevators state
     *
     * @var BuildingElevatorsState
     */
    protected $state;

    /**
     * Service Constructor 
     * 
     * Creates a Service for attending Elevator Calls,
     * which choses the right elevator by applying an Strategy
     *
     * @param Building $building The building with Elevators
     * @param ElevatorCalledStrategy $strategy The strategy to apply
     * @param StateFactory $factory A factory helping the creation of an State of the Building
     * 
     * @internal The State should be managed indeed in a Domain Service, which may be Observing the Elevator Entities
     * 
     */
    public function __construct(Building $building, ElevatorCalledStrategy $strategy, StateFactory $factory)
    {
        // TODO: Move the State management to a Domain Service Observing Elevators of each building
        // That service may offer the state given a building

        $this->strategy = $strategy;
        $this->building = $building;
        $this->state = $factory->create($building);
    }

    /**
     * Handles a call to an elevator
     * 
     * @internal Uses an strategy to determine which is the best elevator to move
     *           The strategy depends on the State of the Elevators in the Building
     *
     * @param ElevatorCalled $event
     * @return void
     */
    public function handle(ElevatorCalled $event)
    {
        // Does nothing if call comes from the destination flat
        if ($event->calling_flat == $event->destination_flat) return;

        $calling_flat = array_search($event->calling_flat,$this->building->getFlats());
        $destination_flat = array_search($event->destination_flat,$this->building->getFlats());

        if ($calling_flat === FALSE)
            throw new \InvalidArgumentException(sprintf("Bad flat %s, for the elevator call",$event->calling_flat));
        if ($destination_flat === FALSE)
            throw new \InvalidArgumentException(sprintf("Bad flat %s, for the elevator destination",$event->destination_flat));
            

        // Looks for the right Elevator using the given strategy
        $elevator_id = $this->strategy->getElevator(
                        $this->state,
                        $event->calling_flat,
                        $event->destination_flat
        );

        if (!is_null($elevator_id)) {            
            // Elevator is moved twice
            $this->building->moveElevator($elevator_id, $event->calling_flat);
            $elevator = $this->building->moveElevator($elevator_id, $event->destination_flat);            
            
            // State is updated with the elevators new position
            // TODO: Move this to the model via Elevators Observer service
            $this->state->setState($elevator);
        }
    }
}