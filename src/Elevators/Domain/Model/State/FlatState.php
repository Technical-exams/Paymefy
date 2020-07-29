<?php namespace Proweb21\Elevators\Model\State;

use Proweb21\Elevators\Model\Building\Elevator;
use Proweb21\Elevators\Model\Building\ElevatorsCollection;
use Proweb21\Elevators\Model\Building\Flat;

/**
 * The elevators in a Flat
 */
class FlatState
{
    
    /**
     * The flat stated
     *
     * @var Flat
     */
    protected $flat;

    
    /**
     * Elevators stated in the flat
     *
     * @var ElevatorsCollection
     */
    protected $elevators;


    public function __construct(Flat $flat)
    {
        $this->flat = $flat;
        $this->elevators = new ElevatorsCollection();
    }

    /**
     * Read-only properties accessor
     *
     * @param string $property
     * @return void
     */
    public function __get(string $property)
    {
        switch($property){
            case "elevators": 
                return $this->getElevators();
            case "flat": 
                return $this->getFlat();
        }
    }

    /**
     * Getter for $elevators
     *
     * @return ElevatorsCollection
     */
    public function getElevators() : ElevatorsCollection
    {
        return $this->elevators;
    }

    /**
     * Getter for $flat
     *
     * @return Flat
     */
    public function getFlat() : Flat
    {
        return $this->flat;
    }

    /**
     * States an elevator in the Flat
     *
     * @param Elevator $elevator
     * @return void
     */
    public function stateElevator(Elevator $elevator)
    {
        $this->elevators->add($elevator);        
    }

    /**
     * Removes an Elevator from the FlatState
     *
     * @param Elevator $elevator
     * @return void
     */
    public function unstateElevator(Elevator $elevator)
    {
        $this->elevators->remove($elevator);
    }
}
