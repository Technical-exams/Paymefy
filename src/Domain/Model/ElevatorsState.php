<?php namespace Proweb21\Elevator\Model;

/**
 * Elevators' state in a building
 * 
 * An state is the location of the Elevators in the Building flats
 * 
 */
final class ElevatorsState
{
    /**
     * Internal state of the Elevators in a Building
     *
     * @var array
     */
    protected $state = [];

    /**
     * Sets the state for an elevator in a Building
     *
     * @param string $elevator id The elevator to state
     * @return string The elevator id once stated
     */
    public function setState(string $elevator, int $flat): string
    {
        $elevator = $this->removeState($elevator);

        if (!array_key_exists($flat, $this->state))        
            $this->state[$flat] = [$elevator];
        else
            $this->state[$flat][]=$elevator;
        
        return $elevator;
    }

    
    /**
     * Removes the state of an elevator in a Building
     *
     * @param string $elevator The elevator which state has to be removed
     * @return string The elevator once its state is removed
     */
    public function removeState(string $elevator) : string
    {
        $state = $this->getState($elevator);
        
        if ($state) {
            array_splice($this->state[$state["flat"]], $state["order"], 1);
            // Flat State is reindexed
            $this->state[$state["flat"]] = array_values($this->state[$state["flat"]]);
        }

        return $elevator;
    }

    /**
     * Gets the state of an Elevator in a Building 
     *
     * @param string $elevator
     * @return array|false the Elevator state or false if not stated in the building
     */
    public function getState(string $elevator)
    {
        
        $result = false;
        $key = false;
        $flat = 0;

        while ($flat<count($this->state) && !$result){
            if (is_array($this->state[$flat]))
                $key = array_search($elevator,$this->state[$flat]);
            if ($key !== false)
                $result = ["flat"=>$flat,"order"=>$key,"elevator"=>$elevator];
            $flat++;
        }

        return $result;
    }

    /**
     * Gets the state of Elevators located in a building flat
     *
     * @param integer $flat
     * @return array list of elevators in the given flat
     */
    public function getFlatState(int $flat)
    {
        $result = [];

        if (array_key_exists($flat,$this->state))
            $result = $this->state[$flat];               

        return $result;
    }


}