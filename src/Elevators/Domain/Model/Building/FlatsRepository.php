<?php namespace Proweb21\Elevators\Model\Building;

/**
 * Repository for Flat instances
 */
interface FlatsRepository
{
    public function findOneByName(string $name, string $building) : ?Flat;
    
    public function findOneByPosition(int $position, string $building) : ?Flat;

    public function findAll() : array;

    public function add(Flat $flat);

    public function remove(Flat $flat);
}
