<?php namespace Proweb21\Elevator\Domain\Strategies;

use Proweb21\Elevators\Model\Building\Elevator;
use Proweb21\Elevators\Model\Building\Flat;
use Proweb21\Elevators\Model\State\BuildingState;

class CloserElevatorStrategy implements ElevatorLookupStrategy
{

    /**
     * {@inheritDoc}
     *
     */
    public function getElevator(BuildingState $state, Flat $calling_flat, Flat $destination_flat) : ?Elevator
    {
        $result = false;
        $offset = 0;

             
        // Lookup at the calling flat if there is an elevator, that is the candidate
        $elevators = $state->getElevators($calling_flat, $offset);
        if (false !== $elevators && count($elevators)) {
            $result = $elevators->first();
        } else {
            $offset = 1;
            do {
                $elevators_upwards = $state->getElevators($calling_flat, $offset);
                $elevators_downwards = $state->getElevators($calling_flat, -$offset);

                // Upwards elevators have preference because of gravity and energy saving lower impact
                if (false !== $elevators_upwards && count($elevators_upwards)) {
                    $result = $elevators_upwards->first();
                } elseif (false !== $elevators_downwards && count($elevators_downwards)) {
                    $result = $elevators_downwards->first();
                } else {
                    $offset++;
                }
            } while (false === $result && (false !== $elevators_upwards || false !== $elevators_downwards));
        }

        return $result ? $result : null;
    }
}
