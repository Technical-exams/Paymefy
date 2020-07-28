<?php namespace Proweb21\Elevator\Model\ElevatorMove;

/**
 * Repository used to collect Elevator Stats
 */
interface ElevatorMoveRepository
{
    /**
     * Adds ElevatorMove to repository
     *
     * @param ElevatorMove $stats
     * @return void
     */
    public function add(ElevatorMove $stats);

    /**
     * Gets the stats of all Elevators
     * @oaram bool $summarized()
     * @return \Traversable
     */
    public function findAll(bool $summarized) : \Traversable;

    /**
     * Gets the last stats of Elevator
     *
     * @return ElevatorMove|null The elevator stats if found or null if no stats in the Repository
     *
     */
    public function last(string $elevator): ?ElevatorMove;


    /**
     * Counts the stats in the repository
     */
    public function count(): int;

    /**
     * Removes all stats in the repository
     */
    public function removeAll();
}
