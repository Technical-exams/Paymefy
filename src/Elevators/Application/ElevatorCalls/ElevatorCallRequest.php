<?php namespace Proweb21\Elevators\Application\ElevatorCalls;

use Proweb21\Command;

/**
 * Elevator Called Command
 *
 * This command occurs when a building user calls an elevator
 *
 * @property-read \DateTimeImmutable $time
 * @property-read string $building
 * @property-read string $calling_flat
 * @property-read string $destination_flat
 */
final class ElevatorCallRequest implements Command
{

    /**
     * Flat id where elevator is called
     *
     * @var string
     */
    public $calling_flat;

    /**
     * Requested flat id
     *
     * @var string
     */
    public $destination_flat;

    
    /**
     * The building where the Elevator is Called
     *
     * @var string
     */
    protected $building;

    /**
     * ElevatorCall Command constructor
     *
     * @param string $calling_flat
     * @param string $destination_flat
     * @param string $building
     */
    public function __construct(string $calling_flat, string $destination_flat, string $building)
    {
        $this->calling_flat = $calling_flat;
        $this->destination_flat = $destination_flat;
        $this->building = $building;
    }

    /**
     * Read-only properties accessor
     *
     * @param string $property
     * @return void
     */
    public function __get(string $property)
    {
        switch ($property) {
            case "building":
                return $this->getBuilding();
            case "calling_flat":
                return $this->getCallingFlat();
            case "destination_flat":
                return $this->getDestinationFlat();
        }
    }

    /**
     * Getter for $building property
     *
     * @return string
     */
    public function getBuilding() : string
    {
        return $this->building;
    }
    
    /**
     * Getter for $calling_flat property
     *
     * @return string
     */
    public function getCallingFlat() : string
    {
        return $this->calling_flat;
    }

    /**
     * Getter for $destination_flat property
     *
     * @return string
     */
    public function getDestinationFlat(): string
    {
        return $this->destination_flat;
    }
}
