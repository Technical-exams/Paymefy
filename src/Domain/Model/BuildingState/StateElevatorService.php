<?php namespace Proweb21\Elevator\Model\BuildingState;

use Proweb21\Elevator\Domain\ObserverTrait;
use Proweb21\Elevator\Events\Observer;
use Proweb21\Elevator\Model\ElevatorsStateFactory as StateFactory;

/**
 * Service responsible of stating elevators in a building
 * 
 */
class StateElevatorsService
    implements Observer
{

    use ObserverTrait;

    /**
     * The Building Elevators state
     *
     * @var ElevatorsState
     */
    protected $state;

    /**
     * The building with elevators
     *
     * @var Building
     */
    protected $building;


    
    /**
     * Service constructor
     *
     * @param Building $building
     * @param Factory $stateFactory
     */
    public function __construct(Building $building)
    {
        $this->building = $building;
        $this->state = StateFactory::create($building);
    
        $this->observe(ElevatorFlatChanged::class, 'updateState');
    }


    /**
     * Getter for the current service state
     *
     * @return FlatStateDTO[]
     */
    public function getState(): array
    {
        $result = [];

        foreach($this->building->getFlats() as $flat_position => $flat){
            $elevators = $this->state->getFlatState($flat_position);            
            $result[$flat_position] = new FlatStateDTO($flat,$elevators);
        }

        return $result;
    }

    /**
     * Updates the state from an ElevatorFlatChanged event
     *
     * @param ElevatorFlatChanged $event
     * @return void
     */
    public function updateState(ElevatorFlatChanged $event)
    {
        $flat = array_search($event->current_flat, $this->building->getFlats());

        $this->state->setState($event->elevator_id, $flat);
    }

}