<?php namespace Proweb21\Elevator\Domain\Events;

use Proweb21\Elevator\Events\EventTrait;
use Proweb21\Elevator\Events\ObservableEvent;

/**
 * ElevatorHasMoved Domain Event
 *
 * This event occurs when an elevator is moved in a building
 *
 * @property-read \DateTimeImmutable $time
 * @property-read string $elevator_id
 * @property-read int $current_flat
 * @property-read int $previous_flat
 */
final class ElevatorHasMoved implements ObservableEvent
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
     * Previous flat position
     *
     * @var int
     */
    protected $previous_flat;


    /**
     * Constructor
     *
     * @param string $elevator_id
     * @param integer $previous_flat
     * @param integer $current_flat
     */
    public function __construct(string $elevator_id, int $previous_flat, int $current_flat)
    {
        $this->elevator_id = $elevator_id;
        $this->previous_flat = $previous_flat;
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
            case 'previous_flat':
                return $this->getPreviousFlat();
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
}
