<?php namespace Proweb21\Elevators\Model\Building;

/**
 * Aggregate Root Entity for a Building with Elevators
 *
 * @property-read string $name
 * @property-read ElevatorsCollection $elevators
 * @property-read Flat[] $flats
 */
final class Building
{    

    /**
     * The Buildings name (identifier)
     *
     * @var string
     */
    protected $name;

    /**
     * Elevators of the building
     *
     * @var ElevatorsCollection
     */
    protected $elevators;

    /**
     * Flats where elevators can stop
     *
     * @var array
     */
    protected $flats = [];


    /**
     * Building constructor
     *
     * Initializes a building with its elevators
     *
     * @param string $name The Building's identifier
     * @param string[] $flats an ascending ordered array with names of building's flats
     * @param integer $elevator_count
     */
    public function __construct(string $name, array $flats)
    {
        $this->name = $name;
        foreach ($flats as $flat_name) {
            $this->createFlat($flat_name);
        }
        $this->elevators = new ElevatorsCollection;
    }


    /**
     * Creates a new Flat in the Building
     *
     * @param string $name
     * @param integer $position
     * @return Flat
     */
    public function createFlat(string $name, int $position = null) : Flat
    {
        if (is_null($position)) {
            $position = count($this->flats);
        }
        $this->flats[$position] = new Flat($name, $position, $this);

        return $this->flats[$position];
    }

    


    /**
     * Building $flats getter
     *
     * @return array The flats of building
     */
    public function getFlats(): array
    {
        return $this->flats;
    }


    /**
     * Read-only properties accessor
     *
     * @param string $property
     * @return void
     */
    public function __get($property)
    {
        switch ($property) {
            case "name":
                return $this->getName();
            break;
            case "elevators":
                return $this->getElevators();
            break;
            case "flats":
                return $this->getFlats();
            break;

        }
    }

    /**
     * Building $elevators getter
     *
     * @return ElevatorsCollection The elevators of the building with their state
     */
    public function getElevators(): ElevatorsCollection
    {
        return $this->elevators;
    }

    /**
     * Building $name getter
     *
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }


    /**
     * Adds a new Elevator to Building
     *
     * @param Flat $flat
     * @return Elevator The elevator created
     *
     * @throws \AssertionError If flat do not exist in the building
     *
     */
    public function createElevator(Flat $flat = null)
    {
        $flat = is_null($flat) ? reset($this->flats) : $flat;
        $this->validateFlat($flat);

        $elevator = new Elevator($flat, $this);
        $this->elevators->add($elevator);
        
        return $elevator;
    }


    /**
     * Moves an Elevator in the Building
     *
     * @param Elevator $elevator
     * @param Flat $to_flat
     * @return Elevator The $elevator moved
     *
     * @throws \AssertionError If elevator or flat do not exist in the building
     * @throws \AssertionError If elevator is already in the given flat
     */
    public function moveElevator(Elevator $elevator, Flat $to_flat)
    {
        $this->validateFlat($to_flat);
        $this->validateElevator($elevator);

        $previous_flat = $elevator->flat;
        if (Flat::equals($previous_flat,$elevator->flat)) {
            throw new \AssertionError("Cannot move an elevator which is already in the destination flat");
        }
        $elevator->move($to_flat);
        
        return $elevator;
    }

    



    /**
     * Checks if a given flat exists in the building
     *
     * @param Flat $flat
     * @return true if flat belongs to Building
     */
    protected function validateFlat(Flat $flat)
    {
        if ((!in_array($flat, $this->flats)) || ($flat->building !== $this)) {
            throw new \AssertionError("Unexisting flat in this building");
        }
        return true;
    }

    /**
     * Checks if a given elevator exists in the building
     *
     * @param Elevator $elevator
     * @return true if elevator exists in the Building
     */
    protected function validateElevator(Elevator $elevator) : bool
    {
        $found = $this->elevators->findOne($elevator->id);
        if (false === $found) {
            throw new \AssertionError("Unexisting elevator in this building");
        }
        return true;
    }
}
