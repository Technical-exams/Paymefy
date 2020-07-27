<?php namespace Proweb21\Elevator\Domain\Services;

use Proweb21\Elevator\Domain\Events\ElevatorCreated;
use Proweb21\Elevator\Domain\ObserverTrait;
use Proweb21\Elevator\Events\Observer;
use Proweb21\Elevator\Model\Building\ElevatorsRepository;
use Proweb21\Elevator\Model\BuildingState\BuildingStatesRepository;

/**
 * Service responsible of updating the state when an elevator is created
 * 
 */
final class ElevatorCreatedService
implements Observer
{

    use ObserverTrait;

    /**
     * Repository with building states
     *
     * @var BuildingStatesRepository
     */
    protected $states_repo;

    /**
     * Repository with elevators
     *
     * @var ElevatorsRepository
     */
    protected $elevators_repo;


    public function __construct(BuildingStatesRepository $states_repo, ElevatorsRepository $elevators_repo)
    {
        $this->states_repo = $states_repo;        
        $this->elevators_repo = $elevators_repo;
        $this->observe(ElevatorCreated::class,'onElevatorCreated');
    }

    /**
     * Reacts to ElevatorCreated Domain Event stating the elevator
     *
     * @param ElevatorCreated $event
     * @return void
     */
    public function onElevatorCreated(ElevatorCreated $event)
    {
        $elevator = $this->elevators_repo->findOne($event->elevator_id,$event->building);
        $state = $this->states_repo->findOne($event->building);

        if ( is_null($elevator) || is_null($state) ){
            throw new \RuntimeException("An unknown elevator was created. Details:\n".
                                        "elevator: ${event->elevator_id}\n".
                                        "building: ${event->building}"
                                        );
        }

        $state->stateElevator($elevator);
    }
}