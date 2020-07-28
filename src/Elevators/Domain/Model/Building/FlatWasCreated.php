<?php namespace Proweb21\Elevators\Model\Building;

use Proweb21\EventTrait;
use Proweb21\ObservableEvent;

/**
 * FlatCreated Domain Event
 *
 * This event occurs when an flat is created in a building
 *
 * @property-read \DateTimeImmutable $time
 * @property-read string $name
 * @property-read int $position
 * @property-read string $building
 */
final class FlatWasCreated implements ObservableEvent
{
    use EventTrait;

    /**
     * Flat name
     *
     * @var string
     */
    protected $name;

    /**
     * Flat position
     *
     * @var int
     */
    protected $position;

    /**
     * Building's identifier
     *
     * @var string
     */
    protected $building;

    public function __construct(string $name, int $position, string $building)
    {
        $this->name = $name;
        $this->position = $position;
        $this->building = $building;
        $this->setTime();
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
            case "building":
                return $this->getBuilding();
            break;
            case "name":
                return $this->getName();
            break;
            case "position":
                return $this->getPosition();
            break;
        }
    }


    /**
     * Getter for $building property
     *
     * @return string
     */
    public function getBuilding() : string
    {
        return $this->building;
    }

    /**
     * Getter for $name property
     *
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }
 
    /**
     * Getter for $position property
     *
     * @return integer
     */
    public function getPosition() : int
    {
        return $this->position;
    }
}
