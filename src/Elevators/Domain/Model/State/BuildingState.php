<?php namespace Proweb21\Elevators\Model\State;

use Proweb21\Elevator\Model\Building\Building;
use Proweb21\Elevators\Model\Building\Elevator;
use Proweb21\Elevators\Model\Building\ElevatorsCollection;
use Proweb21\Elevators\Model\Building\Flat;

/**
 * Elevators' state in a building
 *
 * An state is the location of the Elevators in the Building flats
 *
 */
final class BuildingState
{

    /**
     * Internal state of the Elevators in a Building
     *
     * @var FlatStateCollection
     */
    protected $flats_state;

    /**
     * Building stated
     *
     * @var Building
     */
    protected $building;


    /**
     * Constructor
     *
     * Creates a "clean" (empty) instance of BuildingState
     * with no Flats neither Elevators
     *
     * @param Building $building
     */
    public function __construct(Building $building)
    {
        $this->building = $building;
        $this->flats_state = new FlatStateCollection($this->building);
    }


    /**
     * States a Flat
     *
     * @param Flat $flat
     * @return $flat The flat once stated
     *
     * @throws \AssertionError if $flat does not belong
     */
    public function stateFlat(Flat $flat)
    {
        if ($flat->building !== $this->building) {
            throw new \AssertionError("Cannot state a Flat of a diferent Building");
        }
        
        if (! isset($this->flats_state[$flat])) {
            $this->flats_state[$flat] = new FlatState($flat);
        }
        return $flat;
    }

    /**
     * States an elevator
     * 
     * This implies updating the building state and stating the elevator
     * into the FlatState matching by the elevator's current flat
     *
     * @param Elevator $elevator
     * @return Elevator The elevator once stated
     * 
     * @throws \AssertionError If elevators does not belong the the building stated
     */
    public function stateElevator(Elevator $elevator) : Elevator
    {
        if ($elevator->building !== $this->building)
            throw new \AssertionError("Trying to state an Elevator of another building");
        
        foreach($this->flats_state as $flat_state){
            $flat_state->unstateElevator($elevator);
        }

        $this->flats_state[$elevator->flat]->stateElevator($elevator);

        return $elevator;
    }

    /**
     * Gets the Elevators stated in a building flat
     *
     * @param integer $flat
     * @return ElevatorsCollection | False
     */
    public function getElevators(Flat $flat, int $offset = 0)
    {
        $result = false;

        if ( ! isset($this->flat_states[$flat]) )
            throw new \InvalidArgumentException("Flat ${flat->name}, is not valid");

        $flat_state = $this->flat_states->seek($flat,$offset);
        if (FALSE !== $flat_state){
            $result = $flat_state->elevators;
        }  
        
        return $result;
    }

}
