<?php namespace Proweb21\Elevator\Model\Building;

/**
 * Collection of Elevators 
 */
final class ElevatorsCollection
    implements \IteratorAggregate
    
{
    /**
     * The list of elevators compounding the collection
     *
     * @var array
     */
    protected $elevators = [];

    /**
     * Required by the \IteratorAggregate interface
     *
     * @return \Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->elevators);
    }

    /**
     * Stores an elevator to the collection if not present
     * 
     * @param Elevator $elevator
     * @return Elevator The elevator added
     */
    public function add(Elevator $elevator): Elevator
    {
        $this->elevators[$elevator->id] = $elevator;
        
        return $elevator;
    }

    /**
     * Removes an elevator from the collection
     *
     * @param Elevator $elevator The elevator to remove from the collection
     * @return Elevator The given elevator once removed (in case it was present)
     */
    public function remove(Elevator $elevator) : Elevator
    {
        if (array_key_exists($elevator->id,$this->elevators))
            unset($this->elevators[$elevator->id]);

        return $elevator;
    }

    /**
     * Finds an elevator in the collection given its id
     *
     * @param string $id
     * @return Elevator|False The elevator with the given id or null if no elevator was found
     */
    public function findOne(string $id)
    {        
        $result = false;

        if (array_key_exists($id,$this->elevators))
            $result = $this->elevators[$id];

        return $result;
    }

    /**
     * Gets the first elevator in the collection
     *
     * @return Elevator|FALSE The first elevator or FALSE if collection is empty
     */
    public function first()
    {
        $result = false;

        if (count($this->elevators))
            $result = reset($this->elevators);
        
        return $result;
    }



}