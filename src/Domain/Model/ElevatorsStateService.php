<?php namespace Proweb21\Elevator\Model;

use Proweb21\Elevator\Domain\DomainEventPublisher;
use Proweb21\Elevator\Events\Observer;
use Proweb21\Elevator\Events\ObservableEventSubject;
use Proweb21\Elevator\Model\ElevatorsStateFactory as StateFactory;

/**
 * Service responsaible of maintaining
 * the state of building elevators
 */
class ElevatorsStateService
    implements Observer
{
    
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
     * Observes for ElevatorFlatChanged Domain Events
     * 
     * Required by the Observer interface
     *
     * @param ObservableEventSubject $events
     * @return void
     */
    public function observe(ObservableEventSubject $events)
    {
        $events->attachObserver($this,'updateState',ElevatorFlatChanged::class);
    }


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

        // publish current state of elevators
        $this->observe(DomainEventPublisher::instance());        
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