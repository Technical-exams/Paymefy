<?php namespace Proweb21\Elevator\Model\BuildingState;

/**
 * Repository for BuildingState instances
 */
interface BuildingStatesRepository
{
    public function findOne(string $building) : ?BuildingState;
        
    public function findAll() : array;

    public function add(BuildingState $state);

    public function remove(BuildingState $state);
}
