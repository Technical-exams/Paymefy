<?php namespace Proweb21\Elevator\Model;

/**
 * DTO for exchanging the elevators state in a flat between Domain and Application
 * 
 */
class FlatStateDTO
{
    /**
     * Flat id (not to be confused with the position in the building)
     *
     * @var int
     */
    public $id;


    /**
     * List of elevators in the flat
     *
     * @var String[]
     */
    public $elevators;

    /**
     * Creates anew FlatStateDTO
     *
     * @param integer $id
     * @param array $elevators
     */
    public function __construct(int $id, array $elevators)
    {
        $this->id = $id;
        $this->elevators = $elevators;
    }
}