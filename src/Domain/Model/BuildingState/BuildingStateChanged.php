<?php namespace Proweb21\Elevator\Model\Building;

use Proweb21\Elevator\Events\ObservableEvent;
use Proweb21\Elevator\Events\EventTrait;

/**
 * Domain Event indicating the Building elevators state has changed
 */
final class BuildingStateChanged
    implements ObservableEvent
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
    protected $flats_moved;

    /**
     * Which elevator has changed its state
     *
     * @var string
     */
    protected $elevator_moved;

    /**
     * Current elevators State, flat by flat
     *
     * @var array
     */
    protected $current_state;

    /**
     * Current flat for the moved Elevator
     *
     * @var int
     */
    protected $elevator_flat;



    /**
     * Constructor
     * 
     * Creates an ElevatorsStateChanged Event instance
     *
     * @param integer $flats_moved
     * @param string $elevator_moved
     * @param array $current_state
     */
    public function __construct(int $flats_moved, string $elevator_moved, string $elevator_flat, array $current_state)
    {
        $this->flats_moved = $flats_moved;
        $this->elevator_moved = $elevator_moved;
        $this->current_state = $current_state;        
        $this->elevator_flat = $elevator_flat;
        $this->setTime();
    }

    public function flats_moved(): int
    {
        return $this->flats_moved;
    }

    public function elevator_flat(): int
    {
        return $this->elevator_flat;
    }

    public function elevator_moved() : string
    {
        return $this->elevator_moved;
    }
    
}