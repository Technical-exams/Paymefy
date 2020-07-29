<?php namespace Proweb21\Elevators\Domain\Lookup;

use Proweb21\Elevator\Domain\DomainService;
use Proweb21\Elevators\Model\Building\Elevator;
use Proweb21\Elevators\Model\Building\Flat;
use Proweb21\Elevators\Model\State\BuildingStatesRepository;

/**
 * Service responsible of looking up for an available elevator in a building
 *
 * Selects a Building's elevator for performing a trip
 *
 */
final class LookupElevator extends DomainService
{

    /**
     * The strategy to use when looking up for an available elevator
     *
     * @var ElevatorLookupStrategy
     */
    protected $strategy;


    /**
     * The repository with the building states
     *
     * @var BuildingStatesRepository
     */
    protected $repository;

    /**
     * Constructor
     *
     * @param ElevatorLookupStrategy $strategy The strategy to use when looking up for elevators
     */
    public function __construct(ElevatorLookupStrategy $strategy, BuildingStatesRepository $repository)
    {
        $this->strategy = $strategy;
        $this->repository = $repository;
    }

    /**
     * Service execution method
     *
     * @param Flat $from_flat
     * @param Flat $to_flat
     * @return Elevator|null
     * 
     * @throws \AssertionError when flats' buildings differ
     */
    public function __invoke(Flat $from_flat, Flat $to_flat) : ?Elevator
    {
        if ($from_flat->building !== $to_flat->building)
            throw new \AssertionError("Cannot lookup for an elevator to perform a trip for different building flats");
        
        $state = $this->repository->findOne($from_flat->building->name);
        
        return $this->strategy->getElevator($state, $from_flat, $to_flat);
    }
}
