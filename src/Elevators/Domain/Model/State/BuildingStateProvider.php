<?php namespace Proweb21\Elevators\Model\State;

use Proweb21\Elevators\Model\Building\Building;

/**
 * Factory for BuildingState instances
 * 
 * Each instance created will be supplied with observers
 * 
 */
final class BuildingStateProvider
{

    protected static $observers = [];

    /**
     * Factory method for creating a Building Elevators State
     *
     * @param Building $building The Building to use for creating the State
     * @return BuildingState The State created from the building elevators
     */
    public static function create(Building $building) : BuildingState
    {
        $result = new BuildingState($building);

        if ( !array_key_exists($building->name, self::$observers) )
        {
            self::createBuildingObservers($building,$result);
        }

        foreach ($building->flats as $flat)
        {
            $result->stateFlat($flat);
        }

        foreach ($building->elevators as $elevator)
        {
            $result->stateElevator($elevator);
        }

        return $result;
    }


    /**
     * Creates observers what will update the State when building entities change
     * 
     * @param Building $building The building to observe
     * @param BuildingState $state The building's state to update
     */
    protected static function createBuildingObservers(Building $building, BuildingState $state)
    {
        self::$observers[$building->name] = [];
        self::$observers[$building->name][] = new FlatWasCreatedObserver($state);
        self::$observers[$building->name][] = new ElevatorWasCreatedObserver($state);
        self::$observers[$building->name][] = new ElevatorHasMovedObserver($state);
    }
}