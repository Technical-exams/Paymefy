<?php namespace Proweb21\Elevator\Infrastructure\Domain\Model\Building\InMemory;

use Proweb21\Elevator\Model\Building\Flat;
use Proweb21\Elevator\Model\Building\FlatsRepository as BaseRepository;

class FlatsRepository implements BaseRepository
{
    /**
     * Flats in memory persistence
     *
     * @var array[]
     */
    protected $flats = [];


    /**
     * {@inheritDoc}
     *
     */
    public function findOneByName(string $name, string $building) : ?Flat
    {
        $result = null;

        if (array_key_exists($building,$this->flats)){
            if (array_key_exists($name, $this->flats[$building])) {
                $result = $this->flats[$building][$name];
            }            
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     *
     */
    public function findOneByPosition(int $position, string $building) : ?Flat
    {
        $result = null;

        if (array_key_exists($building,$this->flats)){
            if (array_key_exists($position, $this->flats[$building])) {
                $result = $this->flats[$building][$position];
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

        foreach($this->flats as $building => $flats)
        {
            foreach(array_filter($flats,function($key){
                return is_int($key);
            }, \ARRAY_FILTER_USE_KEY) as $flat){
                $result[]=$flat;
            }

        }

        return $result;
    }


    /**
     * {@inheritDoc}
     *
     */
    public function add(Flat $flat)
    {
        if (!isset($this->flats[$flat->building->name])){
            $this->flats[$flat->building->name] = [];
        }

        $this->flats[$flat->building->name][$flat->position] = $flat;
        $this->flats[$flat->building->name][$flat->name] = $flat;
    }

    /**
     * {@inheritDoc}
     *
     */
    public function remove(Flat $flat)
    {
        if (isset($this->flats[$flat->building->name])){
            $flats = $this->flats[$flat->building->name];
            isset($flats[$flat->position]) && unset($flats[$flat->position]);
            isset($flats[$flat->name]) && unset($flats[$flat->name]);
            $this->flats[$flat->building->name] = $flats;
        }
    }


}