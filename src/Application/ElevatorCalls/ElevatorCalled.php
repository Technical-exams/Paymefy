<?php namespace Proweb21\Elevator\Application\ElevatorCalls;

use Proweb21\Elevator\Events\Event;
use Proweb21\Elevator\Events\EventTrait;

/**
 * Elevator Called command event
 * 
 * This command occurs when a building user calls an elevator
 * 
 * @property-read \DateTimeImmutable $time
 */
final class ElevatorCalled
    implements Event
{

    use EventTrait;

    /**
     * Flat id where elevator is called
     *
     * @var int
     */
    public $calling_flat;

    /**
     * Requested flat id
     *
     * @var int
     */
    public $destination_flat;

    
    /**
     * ElevatorCall Command constructor
     *
     * @param integer $calling_flat
     * @param integer $destination_flat     
     */
    public function __construct(int $calling_flat, int $destination_flat)
    {
        $this->calling_flat = $calling_flat;
        $this->destination_flat = $destination_flat;
        $this->setTime(); // System time is acquired
    }
    
}