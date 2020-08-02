<?php namespace Proweb21\Elevators\Model\State;


use Proweb21\Elevators\Common\Domain\DomainEventObserver;
// use Proweb21\Elevators\Model\Building\FlatsRepository;
use Proweb21\Elevators\Model\Building\Flat;
use Proweb21\Elevators\Model\Building\FlatWasCreated;

class FlatWasCreatedObserver extends DomainEventObserver
{


    protected $state;

    protected $repository;

    public function __construct(BuildingState $state /**, FlatsRepository $repository */)
    {
        $this->state = $state;
        //$this->repository = $repository;
        $this->observe(Flat::class);
    }

    
    /**
     * {@inheritDoc}
     *
     * @return array
     */    
    public function getObservedEvents() : array
    {
        return [FlatWasCreated::class];
    }

    /**
     * Updates the Observer
     *
     * @param Flat $subject
     * @param FlatWasCreated $event
     * @return void
     * 
     * @throws \RuntimeException if $subject is not a Flat instance
     * or $event is not a FlatWasCreated instance
     * 
     */
    public function update($flat, $event)
    {
        if (! ($flat instanceof Flat))
        {
            throw new \RuntimeException("Getting updated by an object which is not the observed DomainSubject");
        }
        if (! ($event instanceof FlatWasCreated))
        {
            throw new \RuntimeException("Getting updated by an event which is not the observed DomainEvent");
        }

        //$flat = $this->repository->findOneByName($event->name, $event->building)
        
        $this->state->stateFlat($flat);
    }

}