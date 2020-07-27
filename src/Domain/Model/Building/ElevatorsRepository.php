<?php namespace Proweb21\Elevator\Model\Building;

/**
 * Repository for Elevator instances
 */
interface ElevatorsRepository
{
    public function findOne(string $elevator) : ?Elevator;

    public function findAll() : ElevatorsCollection;

    public function add(Elevator $elevator);

    public function remove(Elevator $elevator);
}
