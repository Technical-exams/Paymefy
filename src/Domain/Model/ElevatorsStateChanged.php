<?php namespace Proweb21\Elevator\Model;

use Proweb21\Elevator\Events\Event;
use Proweb21\Elevator\Events\EventTrait;

/**
 * Domain Event indicating the Building elevators state has changed
 */
final class ElevatorsStateChanged
    implements Event
{

    /**
     * Provides $time property
     * and time methods
     */
    use EventTrait;

    /**
     * How many flats the moved elevator has tripped
     *
     * @var int
     */
    public $flats_moved;

    /**
     * Which elevator has changed its state
     *
     * @var string
     */
    public $elevator_moved;

    /**
     * Current elevators State, flat by flat
     *
     * @var array
     */
    public $current_state;


    /**
     * Constructor
     * 
     * Creates an ElevatorsStateChanged Event instance
     *
     * @param integer $flats_moved
     * @param string $elevator_moved
     * @param array $current_state
     */
    public function __construct(int $flats_moved, string $elevator_moved, array $current_state)
    {
        $this->flats_moved = $flats_moved;
        $this->elevator_moved = $elevator_moved;
        $this->current_state = $current_state;        
    }
}