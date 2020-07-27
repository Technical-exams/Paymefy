<?php namespace Proweb21\Elevator\Domain\Strategies;

// use Proweb21\Elevator\Model\ElevatorsState;

class CloserElevatorStrategy implements ElevatorLookupStrategy
{

    /**
     * {@inheritDoc}
     *
     */
    public function getElevator(array $flats_state, int $calling_flat, int $destination_flat) : string
    {
        $result = false;
        
        $calling_flat = $this->getCallingFlatPosition($calling_flat, $flats_state);
        // We must find the closer elevator to calling flat
        $upwards = $calling_flat+1;
        $ended_upwards = false;
        $downwards = $calling_flat-1;
        $ended_downwards = false;

        // Look at the calling flat if there is an elevator, that is the candidate
        if (count($flats_state[$calling_flat]->elevators)) {
            $result = reset($flats_state[$calling_flat]->elevators);
        }

        while (($result === false) && (! ($ended_downwards && $ended_upwards))) {
            // Upwards elevators have preference because of gravity and energy saving lower impact
            if ($upwards < count($flats_state)) {
                $result = count($flats_state[$upwards]->elevators) ? reset($flats_state[$upwards]->elevators) : false;
                $upwards++;
            } else {
                $ended_upwards = true;
            }
            if (($result === false) && ($downwards >=0)) {
                $result = count($flats_state[$downwards]->elevators) ? reset($flats_state[$downwards]->elevators) : false;
                $downwards--;
            } else {
                $ended_downwards = true;
            }
        }
        return $result;
    }


    /**
     * Determines the position of a flat in the building
     *
     * @param integer $flat_id The id of the Flat
     * @param FlatStateDTO[] $flats_state The building elevators state as an array of FlatStates
     * @return integer The flat position once found
     *
     * @throws \RuntimeException if flat is not found in the state
     */
    protected function getCallingFlatPosition(int $flat_id, array $flats_state): int
    {
        $result = null; // We want an exception to occur if flat is not found

        foreach ($flats_state as $flat => $flatState) {
            if ($flatState->id === $flat_id) {
                $result = $flat;
                break;
            }
        }

        return $result;
    }
}
