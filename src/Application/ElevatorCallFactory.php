<?php namespace Proweb21\Elevator\Application;

/**
 * Factory for creating ElevatorCalled instances
 * This factory is useful for creating multiple instances with a predetermined flats
 */
class ElevatorCallFactory
{
    /**
     * Where the Elevator was Called 
     *
     * @var int
     */
    protected $calling_flat;

    /**
     * Which was the Elevator Call destination
     *
     * @var int
     */    
    protected $destination_flat;

    /**
     * Factory constructor
     *
     * @param integer $calling_flat
     * @param integer $destination_flat
     */
    public function __construct(int $calling_flat, int $destination_flat)
    {
        $this->calling_flat = $calling_flat;
        $this->destination_flat = $destination_flat;
    }

    /**
     * Creates a new instance of EllevatorCalled
     *
     * @return ElevatorCalled
     */
    public function create() : ElevatorCalled
    {        
        return new ElevatorCalled($this->calling_flat,$this->destination_flat);
    }

}