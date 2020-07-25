<?php namespace Proweb21\Elevator\Model\Building;

use Proweb21\Elevator\Events\EventTrait;
use Proweb21\Elevator\Events\ObservableEvent;

/**
 * ElevatorFlatChanged Domain Event
 * 
 * This command occurs when an elevator flat has changed
 * 
 * @property-read \DateTimeImmutable $time
 */
final class ElevatorFlatChanged
    implements ObservableEvent
{
    /**
     * Provides $time property
     * and time methods
     */
    use EventTrait;

    /**
     * Identifier of the Elevator 
     *
     * @var Elevator
     */
    protected $elevator;

    /**
     * Current flat id
     *
     * @var int
     */
    protected $current_flat;


    /**
     * Previous flat id
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
     * Getter for $elevator
     *
     * @return Elevator
     */
    public function elevator(): Elevator
    {
        return $this->elevator;
    }

    /**
     * Getter for $current_flat
     *
     * @return integer
     */
    public function current_flat(): int
    {
        return $this->current_flat;
    }

    /**
     * Getter for $previous_flat
     *
     * @return integer
     */
    public function previous_flat(): int
    {
        return $this->previous_flat;
    }


}