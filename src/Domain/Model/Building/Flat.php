<?php namespace Proweb21\Elevator\Model\Building;

/**
 * Flat in a Building
 * 
 * @property-read string $name
 * @property-read int $position
 */
class Flat
{
    /**
     * Flat identifier
     *
     * @var string
     */
    protected $name;

    /**
     * Flat position in a building
     *
     * @var int
     */
    protected $position;

    public function __construct(string $name, int $position)
    {
        $this->name = $name;
        $this->position = $position;
    }

    public function __get(string $property)
    {
        switch($property){
            case "name":
                return $this->name;
            break;
            case "position":
                return $this->position;
            break;            
        }
    }

    public function name() : string
    {
        return $this->name;
    }

    public function position() : int
    {
        return $this->position;
    }
}