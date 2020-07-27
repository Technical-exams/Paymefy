<?php namespace Proweb21\Elevator\Model\Building;

/**
 * Service responsible of moving elevators in a building
 *
 * Moves a chosen elevator from one flat to another, serving building elevators use
 *
 */
final class MoveElevatorService
{
    /**
     * Service execution method
     *
     * @param Building $building
     * @param Elevator $elevator
     * @param Flat $from_flat
     * @param Flat $to_flat
     * @return Elevator
     */
    public function __invoke(Building $building, Elevator $elevator, Flat $from_flat, Flat $to_flat)
    {
        // Elevator has to move to the from flat
        if (! Flat::equals($elevator->flat, $from_flat)) {
            $building->moveElevator($elevator, $from_flat);
        }
        // Then has to be moved to the destination flat ($to_flat)
        if (! Flat::equals($elevator->flat, $to_flat)) {
            $building->moveElevator($elevator, $to_flat);
        }
        return $elevator;
    }
}
