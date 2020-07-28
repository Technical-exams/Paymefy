<?php namespace Proweb21\Elevator\Domain\Services;

use Proweb21\Elevator\Domain\Strategies\ElevatorLookupStrategy;
use Proweb21\Elevator\Model\Building\Elevator;
use Proweb21\Elevator\Model\Building\Flat;
use Proweb21\Elevator\Model\BuildingState\BuildingState;

/**
 * Service responsible of looking up for an available elevator in a building
 *
 * Selects a Building's elevator for performing a trip
 *
 */
final class LookupElevatorService
{

    /**
     * Undocumented variable
     *
     * @var ElevatorLookupStrategy
     */
    protected $strategy;

    /**
     * Constructor
     *
     * @param ElevatorLookupStrategy $strategy The strategy to use when looking up for elevators
     */
    public function __construct(ElevatorLookupStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * Service execution method
     *
     * @param BuildingState $state
     * @param Flat $from_flat
     * @param Flat $to_flat
     * @return Elevator|null
     */
    public function __invoke(BuildingState $state, Flat $from_flat, Flat $to_flat) : ?Elevator
    {
        return $this->strategy->getElevator($state, $from_flat, $to_flat);
    }
}
