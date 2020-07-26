<?php namespace Proweb21\Elevator\Domain\Events;

use Proweb21\Elevator\Events\EventTrait;
use Proweb21\Elevator\Events\ObservableEvent;

/**
 * ElevatorCreated Domain Event
 *
 * This event occurs when an elevator is created in a building
 *
 * @property-read \DateTimeImmutable $time
 * @property-read string $elevator_id
 * @property-read int $current_flat
 */
final class ElevatorCreated implements ObservableEvent
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
     * Current flat position
     *
     * @var int
     */
    protected $current_flat;

    /**
     * Constructor
     *
     * @param string $elevator_id
     * @param integer $current_flat
     */
    public function __construct(string $elevator_id, int $current_flat)
    {
        $this->elevator_id = $elevator_id;
        $this->current_flat = $current_flat;
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
            case 'current_flat':
                return $this->getCurrentFlat();
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
}
