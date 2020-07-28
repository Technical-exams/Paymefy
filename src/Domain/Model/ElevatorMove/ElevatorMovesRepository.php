<?php namespace Proweb21\Elevator\Model\ElevatorMove;

/**
 * Repository for ElevatorMove instances
 */
interface ElevatorMovesRepository
{
    /**
     * Adds ElevatorMove to repository
     *
     * @param ElevatorMove $move
     * @return void
     */
    public function add(ElevatorMove $move);

    /**
     * Removes all moves in the repository
     */
    public function clear();
}
