<?php namespace Proweb21\Elevator\Model\ElevatorStats;

/**
 * Repository used to collect Elevator Stats
 */
interface ElevatorStatsRepository
{
    /**
     * Adds ElevatorStats to repository
     *
     * @param ElevatorsStats $stats
     * @return void
     */
    public function add(ElevatorStats $stats);

    /**
     * Gets the stats of all Elevators
     * @oaram bool $summarized()
     * @return \Traversable
     */
    public function findAll(bool $summarized) : \Traversable;

    /**
     * Gets the last stats of Elevator
     *
     * @return ElevatorStats|null The elevator stats if found or null if no stats in the Repository
     *
     */
    public function last(string $elevator): ?ElevatorStats;


    /**
     * Counts the stats in the repository
     */
    public function count(): int;

    /**
     * Removes all stats in the repository
     */
    public function removeAll();
}
