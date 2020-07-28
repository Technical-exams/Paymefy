<?php namespace Proweb21\Elevators\Model\Building;

/**
 * Repository for Elevator instances
 */
interface ElevatorsRepository
{
    public function findOne(string $elevator_id) : ?Elevator;

    public function findAll() : array;

    public function add(Elevator $elevator);

    public function remove(Elevator $elevator);
}
