<?php namespace Proweb21\Elevator\Model\Building;

/**
 * Repository for Elevator instances
 */
interface ElevatorsRepository
{
    public function findOne(string $elevator, string $building) : ?Elevator;

    public function findAll() : array;

    public function add(Elevator $elevator);

    public function remove(Elevator $elevator);
}
