<?php namespace Proweb21\Elevator\Domain\Strategies;

/**
 * Interface for ElevatorCalledStrategy classes
 *
 * An ElevatorCalledStrategy is an algorithm with
 * the logic behind the selection of an Elevator candidate
 * for attending an Elevator call
 *
 */
interface ElevatorCalledStrategy
{
  
    /**
     * Returns the best elevator candidate for a trip starting at calling flat and ending at destination flat
     *
     * @param FlatStateDTO[] $state The elevators state for each flat in the building
     * @param integer $calling_flat The flat where the elevator is called
     * @param integer $destination_flat The flat selected as trip destination
     * @return string|null The best building elevator for performing the trip
     */
    public function getElevator(array $flats_state, int $calling_flat, int $destination_flat) : ?string;
}
