<?php namespace Proweb21\Elevators\Model\Building;

use Proweb21\Elevators\Common\Domain\DomainEvent;
use Proweb21\Event;

/**
 * A DTO Domain Event indicating that a Flat was Created
 *
 * This event occurs when an flat is created in a building
 *
 * @property-read \DateTimeImmutable $time
 * @property-read string $name
 * @property-read int $position
 * @property-read string $building
 *
 * {@inheritDoc}
 *
 */
final class FlatWasCreated extends DomainEvent implements Event
{

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

    public function __construct(Flat $flat)
    {
        parent::__construct($flat);
        $this->name = $flat->name;
        $this->position = $flat->position;
        $this->building = $flat->building->name;
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
            default:
                return parent::__get($property);
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
