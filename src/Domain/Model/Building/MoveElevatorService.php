<?php namespace Proweb21\Elevator\Model\Building;

/**
 * Service responsible of moving elevators of a building
 * 
 */
final class MoveElevatorService
{
    /**
     * Service execution method
     *
     * @param Building $building
     * @param Elevator $elevator
     * @param Flat $to_flat
     * @return Elevator
     */
    public function __invoke(Building $building, Elevator $elevator, Flat $to_flat)
    {
        return $building->moveElevator($elevator,$to_flat);
    }
}