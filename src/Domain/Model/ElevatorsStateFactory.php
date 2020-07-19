<?php namespace Proweb21\Elevator\Model;

/**
 * Factory for creating instances of a BuildingElevatorState
 * 
 * Each instance created depends on a building an its Elevators
 * 
 */
abstract class ElevatorsStateFactory
{
    /**
     * Factory method for creating a Building Elevators State
     *
     * @param Building $building The Building to use for creating the State
     * @return ElevatorsState The State created from the building elevators
     */
    public static function create(Building $building) : ElevatorsState
    {
        $result = new ElevatorsState();        

        foreach($building->getElevators() as $elevator){
            $flat = array_search($elevator->flat,$building->getFlats());
            $result->setState($elevator, $flat);
        }

        return $result;
    }
}