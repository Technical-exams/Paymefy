<?php namespace Proweb21\Elevator\Model;

/**
 * Interface descriving a READ-WRITE Repository of Elevators
 * 
 */
interface ElevatorRepository
{
    /**
     * Stores an elevator to the repository if not present
     * 
     * @param Elevator $elevator
     * @return Elevator The elevator added
     */
    public function save(Elevator $elevator): Elevator;
    
     /**
     * Finds an elevator in the repository given its id
     *
     * @param string $id
     * @return Elevator|False The elevator with the given id or null if no elevator was found
     */
    public function findOne(string $id);

    /**
     * Removes an elevator from the repository
     *
     * @param Elevator $elevator The elevator to remove from the repository
     * @return Elevator The given elevator once removed (in case it was present)
     */
    public function remove(Elevator $elevator) : Elevator;
}