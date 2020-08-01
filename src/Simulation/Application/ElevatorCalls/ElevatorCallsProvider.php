<?php namespace Proweb21\Elevator\Application\ElevatorCalls;

use Proweb21\Elevators\Application\ElevatorCalls\ElevatorCallRequest;
use Proweb21\Elevators\Common\Application\ApplicationService;
use Proweb21\Elevators\Model\Building\Flat;

/**
 * Factory for creating ElevatorCalled instances
 * This factory is useful for creating multiple instances with a predetermined flats
 */
final class ElevatorCallsProvider implements ApplicationService
{
    /**
     * Where the Elevator was Called
     *
     * @var Flat
     */
    protected $calling_flat;

    /**
     * Which was the Elevator Call destination
     *
     * @var Flat
     */
    protected $destination_flat;

    /**
     * Factory constructor
     *
     * @param Flat $calling_flat
     * @param Flat $destination_flat
     * @throws \AssertionError if flats do not belong to same building
     */
    public function __construct(Flat $calling_flat, Flat $destination_flat)
    {
        if ($calling_flat->building !== $destination_flat->building) {
            throw new \AssertionError("Flats are not of the same building");
        }

        $this->calling_flat = $calling_flat;
        $this->destination_flat = $destination_flat;
    }

    /**
     * Creates a new instance of EllevatorCalled
     *
     * @return ElevatorCalled
     */
    public function __invoke() : ElevatorCallRequest
    {
        return new ElevatorCallRequest(
            $this->calling_flat->name,
            $this->destination_flat->name,
            $this->calling_flat->building->name
        );
    }
}
