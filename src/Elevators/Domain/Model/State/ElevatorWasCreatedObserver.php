<?php namespace Proweb21\Elevators\Model\State;


use Proweb21\Elevator\Domain\DomainEventObserver;
// use Proweb21\Elevator\Infrastructure\Domain\Model\Building\InMemory\ElevatorsRepository;
use Proweb21\Elevators\Model\Building\Elevator;
use Proweb21\Elevators\Model\Building\ElevatorWasCreated;

class ElevatorWasCreatedObserver extends DomainEventObserver
{


    protected $state;

    protected $repository;

    public function __construct(BuildingState $state /**, ElevatorsRepository $repository */)
    {
        $this->state = $state;
        //$this->repository = $repository;
        $this->observe(Elevator::class);
    }

    
    /**
     * {@inheritDoc}
     *
     * @return array
     */    
    public function getObservedEvents() : array
    {
        return [ElevatorWasCreated::class];
    }

    /**
     * Updates the Observer
     *
     * @param Elevator $elevator
     * @param ElevatorWasCreated $event
     * @return void
     * 
     * @throws \RuntimeException if $elevator is not an Elevator instance
     * or $event is not a Domain Event instance
     * 
     */
    public function update($elevator, $event)
    {
        if (! ($elevator instanceof Elevator))
        {
            throw new \RuntimeException("Getting updated by an object which is not the observed DomainSubject");
        }
        if (! ($event instanceof ElevatorWasCreated))
        {
            throw new \RuntimeException("Getting updated by an event which is not the observed DomainEvent");
        }

        //$elevator = $this->elevator_repository->findOne($event->elevator_id);
        
        $this->state->stateElevator($elevator);
    }

}