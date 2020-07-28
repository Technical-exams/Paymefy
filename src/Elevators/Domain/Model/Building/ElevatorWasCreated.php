<?php namespace Proweb21\Elevators\Model\Building;

use Proweb21\EventTrait;
use Proweb21\ObservableEvent;

/**
 * ElevatorCreated Domain Event
 *
 * This event occurs when an elevator is created in a building
 *
 * @property-read \DateTimeImmutable $time
 * @property-read string $elevator_id
 * @property-read string $building
 */
final class ElevatorWasCreated implements ObservableEvent
{
    /**
     * Provides $time property
     * and time methods
     */
    use EventTrait;

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
     * @param string $elevator_id
     * @param string $building
     */
    public function __construct(string $elevator_id, string $building)
    {
        $this->elevator_id = $elevator_id;
        $this->building = $building;
        // Time is set to current SystemTime via EventTrait
        $this->setTime();
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
