<?php namespace Proweb21\Elevator\Domain\Strategies;

use Proweb21\Elevator\Model\Building\Elevator;
use Proweb21\Elevator\Model\Building\Flat;
use Proweb21\Elevator\Model\BuildingState\BuildingState;

/**
 * Interface for ElevatorCalledStrategy classes
 *
 * An ElevatorCalledStrategy is an algorithm with
 * the logic behind the selection of an Elevator candidate
 * for attending an Elevator call
 *
 */
interface ElevatorLookupStrategy
{
  
    /**
     * Returns the best elevator candidate for a trip starting at calling flat and ending at destination flat
     *
     * @param BuildingState $state The elevators state in the building
     * @param Flat $calling_flat The flat where the elevator is called
     * @param Flat $destination_flat The flat selected as trip destination
     * @return Elevator The best building elevator for performing the trip or NULL if no elevator found
     */
    public function getElevator(BuildingState $state, Flat $calling_flat, Flat $destination_flat) : ?Elevator;
}
