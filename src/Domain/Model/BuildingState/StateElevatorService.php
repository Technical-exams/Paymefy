<?php namespace Proweb21\Elevator\Model\BuildingState;

use Proweb21\Elevator\Model\Building\Elevator;

/**
 * Service responsible of stating elevators in a building
 * 
 */
final class StateElevatorsService
{
    /**
     * Service execution method
     *
     * @param Elevator $elevator
     * @param BuildingState $state
     * @return BuildingState
     */
    public function __invoke(Elevator $elevator, BuildingState $state)
    {
        $state->stateElevator($elevator);

        return $state;
    }

}