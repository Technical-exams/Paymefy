<?php namespace Proweb21\Elevators\Model\Building;

use Proweb21\Elevators\Common\Domain\DomainEvent;
use Proweb21\Event;

/**
 * A DTO Domain Event indicating that an Elevator has Moved
 *
 * This event occurs when an elevator is moved in a building
 *
 * @property-read \DateTimeImmutable $time
 * @property-read string $elevator_id
 * @property-read int $current_flat
 * @property-read int $previous_flat
 * 
 * {@inheritDoc}
 * 
 */
final class ElevatorHasMoved extends DomainEvent implements Event
{

    /**
     * Identifier of the Elevator
     *
     * @var String
     */
    protected $elevator_id;

    /**
     * Current flat position
     *
     * @var int
     */
    protected $current_flat;


    /**
     * Previous flat position
     *
     * @var int
     */
    protected $previous_flat;

    /**
     * Building's identifier
     *
     * @var string
     */
    protected $building;


    /**
     * Constructor
     *
     * @param Elevator $elevator which has been the elevator 
     * @param Flat $previous_flat
     */
    public function __construct(Elevator $elevator, Flat $previous_flat)
    {
        parent::__construct($elevator);
        $this->elevator_id = $elevator->id;
        $this->previous_flat = $previous_flat->position;
        $this->current_flat = $elevator->flat->position;
        $this->building = $elevator->building;
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
            case 'current_flat':
                return $this->getCurrentFlat();
            case 'previous_flat':
                return $this->getPreviousFlat();
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
     * Getter for $current_flat
     *
     * @return integer
     */
    public function getCurrentFlat(): int
    {
        return $this->current_flat;
    }

    /**
     * Getter for $previous_flat
     *
     * @return integer
     */
    public function getPreviousFlat(): int
    {
        return $this->previous_flat;
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
