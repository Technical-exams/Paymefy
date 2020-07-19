<?php namespace Proweb21\Elevator\Application;

use Proweb21\Elevator\Model\ElevatorsState;

class CloserElevatorStrategy
    implements ElevatorCalledStrategy
{

    /**
     * {@inheritDoc}
     * 
     */
    public function getElevator(array $flats_state, int $calling_flat, int $destination_flat) : string
    {
        $result = FALSE;
        
        $calling_flat = $this->getCallingFlatPosition($calling_flat,$flats_state);
        // We must find the closer elevator to calling flat
        $upwards = $calling_flat+1;
        $downwards = $calling_flat-1;      

        // Look at the calling flat if there is an elevator, that is the candidate
        if (count($flats_state[$calling_flat]->elevators))
            $result = reset($flats_state[$calling_flat]->elevators);

        while ($result === FALSE){
            // Upwards elevators have preference because of gravity and energy saving lower impact
            $result = count($flats_state[$upwards]->elevators) ? reset($flats_state[$upwards]->elevators) : FALSE;
            if ($result === FALSE)
                $result = count($flats_state[$downwards]->elevators) ? reset($flats_state[$downwards]->elevators) : FALSE;
            
            if ($upwards < count($flats_state)-1) $upwards++;
            if ($downwards >0) $downwards--;
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

        foreach($flats_state as $flat => $flatState)
        {
            if ($flatState->flat->id === $flat_id){
                $result = $flat;
                break;
            }
        }

        return $result;
    }
}
