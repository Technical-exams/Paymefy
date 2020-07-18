<?php namespace Proweb21\Elevator\Model;

/**
 * Base class for ElevatorsRepositories
 */
abstract class AbstractElevatorRepository
    implements ElevatorRepository
{
    /**
     * The list of elevators compounding the collection
     *
     * @var array
     */
    protected $elevators = [];

    /**
     * {@inheritDoc}
     *
     */    
    public function save(Elevator $elevator): Elevator
    {
        $this->elevators[$elevator->id] = $elevator;
        return $elevator;
    }

    /**
     * {@inheritDoc}
     *
     */
    public function remove(Elevator $elevator) : Elevator
    {
        if (array_key_exists($elevator->id,$this->elevators))
            unset($this->elevators[$elevator->id]);
        return $elevator;
    }

    /**
     * {@inheritDoc}
     *
     */
    public function findOne(string $id)
    {        
        $result = false;
        
        if (array_key_exists($id,$this->elevators))
            $result = $this->elevators[$id];

        return $result;
    }

}