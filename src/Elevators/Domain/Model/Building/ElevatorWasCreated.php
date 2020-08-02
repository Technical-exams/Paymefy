<?php namespace Proweb21\Elevators\Model\Building;

use Proweb21\Elevators\Common\Domain\DomainEvent;
use Proweb21\Event;

/**
 * A DTO Domain Event indicating that an Elevator was Created
 *
 * This event occurs when an elevator is created in a building
 *
 * @property-read \DateTimeImmutable $time
 * @property-read string $elevator_id
 * @property-read string $building
 * 
 * {@inheritDoc}
 * 
 */
final class ElevatorWasCreated extends DomainEvent implements Event
{

    /**
     * Identifier of the Elevator
     *
     * @var String
     */
    protected $elevator_id;

    /**
     * Building's identifier
     *
     * @var string
     */
    protected $building;

    /**
     * Constructor
     *
     * @param Elevator $elevator
     *
     */
    public function __construct(Elevator $elevator)
    {
        parent::__construct($elevator);
        $this->elevator_id = $elevator->id;
        $this->building = $elevator->building->name;
    }


    /**
     * Read-only properties
     *
     * @param string $property
     * @return void
     */
    public function __get(string $property)
    {
        switch ($property) {
            case 'time':
                return $this->getTime();
            case 'elevator_id':
                return $this->getElevatorId();
            case 'building':
                return $this->getBuilding();
            default:
                return parent::__get($property);
        }
    }


    /**
     * Getter for $elevator_id
     *
     * @return string
     */
    public function getElevatorId(): string
    {
        return $this->elevator_id;
    }

    /**
     * Getter for $building
     *
     * @return string
     */
    public function getBuilding() : string
    {
        return $this->building;
    }
}
