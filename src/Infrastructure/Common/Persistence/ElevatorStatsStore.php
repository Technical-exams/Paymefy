<?php namespace Proweb21\Elevator\Infrastructure\Common\Persistence;

use Proweb21\Elevator\Model\ElevatorStats;

/**
 * Interface for Elevator Stats Stores
 * 
 */
interface ElevatorStatsStore
{
    /**
     * Adds a new stats to the store
     *
     * @param ElevatorStats $stats
     * @return bool FALSE if error occurred, TRUE on success
     */
    public function appendOne(ElevatorStats $stats) : bool;


    /**
     * Adds several stats at once to the store
     *
     * @param array $queue_of_stats
     * @return bool FALSE if error occurred, TRUE on success
     */
    public function appendMany(array $queue_of_stats) : bool;

    /**
     * Gets all stored stats 
     * filtered by criteria
     *
     * @internal The specification pattern must be applied here
     * @param array $criteria Which conditions to match when retrieving
     * @return \Traversable
     */
    public function retrieveMany(array $criteria = []) : \Traversable;

    
    /**
     * Gets most recent stored stats 
     * filtered by criteria
     *
     * @internal The specification pattern must be applied here
     * @param array $criteria Which conditions to match when retrieving
     * @return ElevatorStats|null The found stats or null if no stats stored matching the criteria
     */    
    public function retrieveNewest(array $criteria = []): ?ElevatorStats;

    /**
     * Gets a summary of the stats, per elevator and minute
     *
     * @return \Traversable
     */
    public function retrieveSummary() : \Traversable;
}