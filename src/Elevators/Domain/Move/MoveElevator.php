<?php namespace Proweb21\Elevators\Domain\Move;

use Proweb21\Elevator\Domain\DomainService;
use Proweb21\Elevators\Model\Building\Elevator;
use Proweb21\Elevators\Model\Building\Flat;

/**
 * Service responsible of moving elevators in a building
 *
 * Moves a chosen elevator from one flat to another, serving building elevators use
 *
 */
final class MoveElevator extends DomainService
{
    /**
     * Service execution method
     *
     * @param Elevator $elevator
     * @param Flat $from_flat
     * @param Flat $to_flat
     *
     * @return Elevator
     *
     * @throws \AssertionError When elevator building is not the same as flats building
     */
    public function __invoke(Elevator $elevator, Flat $from_flat, Flat $to_flat)
    {
        if (($elevator->building !== $from_flat->building) ||
             ($elevator->building !== $to_flat->building)
        ) {
            throw new \AssertionError("Cannot move an elevator to a flat of a different building");
        }

        // Elevator has to move to the from flat
        if (! Flat::equals($elevator->flat, $from_flat)) {
            $elevator->building->moveElevator($elevator, $from_flat);
        }
        // Then has to be moved to the destination flat ($to_flat)
        if (! Flat::equals($elevator->flat, $to_flat)) {
            $elevator->building->moveElevator($elevator, $to_flat);
        }
        return $elevator;
    }
}
