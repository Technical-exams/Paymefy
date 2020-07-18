<?php namespace Proweb21\Elevator\Model;

use Proweb21\Elevator\Domain\DomainEventPublisher;
use Proweb21\Elevator\Events\EventBus;
use Proweb21\Elevator\Events\EventObserver;
use Proweb21\Elevator\Events\EventPublisher;
use Proweb21\Elevator\Events\EventPublisherTrait;
use Proweb21\Elevator\Events\ObservableEventSubject;
use Proweb21\Elevator\Model\BuildingElevatorsStateFactory as StateFactory;

/**
 * Service responsaible of maintaining
 * the state of building elevators
 */
class ElevatorsStateService
    implements EventPublisher,
               EventObserver
{
    
    /**
     * Implements EventPublisher interface
     */
    use EventPublisherTrait;

    /**
     * The Building Elevators state
     *
     * @var BuildingElevatorsState
     */
    protected $state;

    /**
     * The building with elevators
     *
     * @var Building
     */
    protected $building;


    /**
     * The bus where to publish state change events
     *
     * @var EventBus
     */
    protected $bus;

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
    public function __construct(Building $building, EventBus $bus)
    {
        $this->building = $building;
        $this->state = StateFactory::create($building);
        $this->bus = $bus;
        $this->observe(DomainEventPublisher::instance());
    }


    /**
     * Getter for the current service state
     *
     * @return array
     */
    public function getState(): array
    {
        $result = [];

        foreach($this->building->getFlats() as $flat_position => $flat)
            $result[]=$this->state->getFlatState($flat_position);
        
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

        // Once the state is updated, must publis the state has channged
        $this->publishStateHasChanged($event);
    }

    /**
     * Publish to service broadcast bus
     * an Event notifying the elevators state has changed
     *
     * @param ElevatorFlatChanged $event
     * @return void
     */
    protected function publishStateHasChanged(ElevatorFlatChanged $event)
    {
        // Displacement has to be calculated using real distances, not flat ids
        $previous_flat = array_search($event->previous_flat, $this->building->getFlats());
        $current_flat = array_search($event->current_flat, $this->building->getFlats());
        $displacement = intval(abs($previous_flat - $current_flat));

        $event = new ElevatorsStateChanged($displacement, $event->elevator_id, $this->getState());
        $this->publish($event, $this->bus);
    }
}