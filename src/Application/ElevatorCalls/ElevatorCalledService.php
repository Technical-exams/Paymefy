<?php namespace Proweb21\Elevator\Application\ElevatorCalls;

use Proweb21\Elevator\Application\ElevatorCalls\Strategies\ElevatorCalledStrategy;
use Proweb21\Elevator\Events\EventHandler;
use Proweb21\Elevator\Model\Building;
use Proweb21\Elevator\Model\ElevatorsStateService as StateService;

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
     * The Building Elevators state Service
     *
     * @var StateService
     */
    protected $state_service;

    /**
     * Service Constructor 
     * 
     * Creates a Service for attending Elevator Calls,
     * which choses the right elevator by applying an Strategy
     *
     * @param Building $building The building with Elevators
     * @param ElevatorCalledStrategy $strategy The strategy to apply
     * @param StateService $service A state management service for the building
     * 
     */
    public function __construct(Building $building, ElevatorCalledStrategy $strategy, StateService $service)
    {
        $this->strategy = $strategy;
        $this->building = $building;
        $this->state_service = $service;
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

        if (!in_array($event->calling_flat,$this->building->getFlats()))
            throw new \InvalidArgumentException(sprintf("Bad flat %s, for the elevator call",$event->calling_flat));
        if (!in_array($event->destination_flat, $this->building->getFlats()))
            throw new \InvalidArgumentException(sprintf("Bad flat %s, for the elevator destination",$event->destination_flat));
            
        // Looks for the right Elevator using the given strategy
        $elevator_id = $this->strategy->getElevator(
                        $this->state_service->getState(),
                        $event->calling_flat,
                        $event->destination_flat
        );

        if (!is_null($elevator_id)) {            
            // Elevator is moved twice
            $this->building->moveElevator($elevator_id, $event->calling_flat);
            $this->building->moveElevator($elevator_id, $event->destination_flat);                        
        }
    }
}