<?php namespace Proweb21\Elevator\Infrastructure\Domain\Model\Building\InMemory;

use Proweb21\Elevator\Model\Building\Elevator;
use Proweb21\Elevator\Model\Building\ElevatorsRepository as BaseRepository;

class ElevatorsRepository implements BaseRepository
{
    /**
     * Elevators in memory persistence
     *
     * @var Elevator[][]
     */
    protected $elevators =[];

    /**
     * {@inheritDoc}
     *
     */
    public function findOne(string $elevator, string $building) : ?Elevator
    {
        $result = null;

        if (array_key_exists($building, $this->elevators)){
            if (isset($this->elevators[$building][$elevator])){
                $result = $this->elevators[$building][$elevator];
            }                
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     *
     */
    public function findAll() : array
    {   
        $result = [];

        foreach($this->elevators as $building => $elevators){
            $result = array_merge($result,array_values($elevators));
        }      

        return $result;
    }

    /**
     * {@inheritDoc}
     *
     */
    public function add(Elevator $elevator)
    {
        if (! isset($this->elevators[$elevator->building->name])) {
            $this->elevators[$elevator->building->name] = [];
        }
        $this->elevators[$elevator->building->name][$elevator->id] = $elevator;
    }

    /**
     * {@inheritDoc}
     *
     */
    public function remove(Elevator $elevator)
    {
        if (isset($this->elevators[$elevator->building->name])){
            $elevators = $this->elevators[$elevator->building->name];
            isset($elevators[$elevator->id]) && unset($elevators[$elevator->id]);
            $this->elevators[$elevator->building->name] = $elevators;
        }
    }
}