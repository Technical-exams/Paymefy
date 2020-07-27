<?php namespace Proweb21\Elevator\Domain\Services;

use Proweb21\Elevator\Domain\Events\FlatCreated;
use Proweb21\Elevator\Domain\ObserverTrait;
use Proweb21\Elevator\Events\Observer;
use Proweb21\Elevator\Model\Building\FlatsRepository;
use Proweb21\Elevator\Model\BuildingState\BuildingStatesRepository;

/**
 * Service responsible of updating the state when a flat is created
 * 
 */
final class FlatCreatedService
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
     * Repository with flats
     *
     * @var FlatsRepository
     */
    protected $flats_repo;

    public function __construct(BuildingStatesRepository $states_repo, FlatsRepository $flats_repo)
    {
        $this->states_repo = $states_repo;
        $this->flats_repo = $flats_repo;
        $this->observe(FlatCreated::class,'onFlatCreated');
    }

    /**
     * Reacts to FlatCreated Domain Event stating the flat
     *
     * @param FlatCreated $event
     * @return void
     */
    public function onFlatCreated(FlatCreated $event)
    {
        $flat = $this->flats_repo->findOneByName($event->name,$event->building);
        $state = $this->states_repo->findOne($event->building);

        if ( is_null($flat) || is_null($state) ){
            throw new \RuntimeException("An unknown flat was created. Details:\n".
                                        "name: ${event->name}\n".
                                        "building: ${event->building}"
                                        );
        }

        $state->stateFlat($flat);        
    }
}