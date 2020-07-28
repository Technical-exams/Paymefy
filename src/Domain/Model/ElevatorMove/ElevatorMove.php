<?php namespace Proweb21\Elevator\Model\ElevatorMove;

use Proweb21\Elevator\Model\Building\Elevator;
use Proweb21\Elevator\Model\Building\Flat;

/**
 * Elevators' movement from one flat to another
 *
 * @property-read Elevator $elevator
 * @property-read Flat $to_flat
 * @property-read Flat $from_flat
 * @property-read \DateTimeImmutable $moved_at
 *
 */
final class ElevatorMove
{
    /**
     * Which is the elevator moved
     *
     * @var Elevator
     */
    protected $elevator;

    /**
     * Which is the flat where elevator has moved to
     *
     * @var Flat
     */
    protected $to_flat;

    /**
     * Which is the flat where elevator has moved from
     *
     * @var Flat
     */
    protected $from_flat;


    /**
     * When the elevator moved
     *
     * @var \DateTimeImmutable
     */
    protected $moved_at;


    public function __construct(Elevator $elevator, Flat $to_flat, Flat $from_flat, \DateTimeImmutable $moved_at)
    {
        $this->elevator = $elevator;
        $this->to_flat = $to_flat;
        $this->from_flat = $from_flat;
        $this->moved_at = $moved_at;
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
            case "elevator":
                return $this->getElevator();
            case "moved_at":
                return $this->getMovedAt();
            case "from_flat":
                return $this->getFromFlat();
            case "to_flat":
                return $this->getToFlat();
        }
    }

    /**
     * Getter for $elevator property
     *
     * @return Elevator
     */
    public function getElevator() : Elevator
    {
        return $this->elevator;
    }

    /**
     * Getter for $moved_at property
     *
     * @return \DateTimeImmutable
     */
    public function getMovedAt() : \DateTimeImmutable
    {
        return $this->moved_at;
    }

    /**
     * Getter for $from_flat property
     *
     * @return Flat
     */
    public function getFromFlat() : Flat
    {
        return $this->from_flat;
    }

    /**
     * Getter for $to_flat property
     *
     * @return Flat
     */
    public function getToFlat() : Flat
    {
        return $this->to_flat;
    }
}
