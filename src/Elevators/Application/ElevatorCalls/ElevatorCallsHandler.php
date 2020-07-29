<?php namespace Proweb21\Elevator\Application\ElevatorCalls;

use Proweb21\Elevators\Application\ElevatorCalls\ElevatorCallRequest;
use Proweb21\Elevators\Domain\Lookup\LookupElevator;
use Proweb21\Elevators\Domain\Move\MoveElevator;
use Proweb21\Elevators\Model\Building\Building;
use Proweb21\Elevators\Model\Building\FlatsRepository;
use Proweb21\Handler;

/**
 * The handler for attending the ElevatorCalled command events
 *
 */
class ElevatorCallsHandler implements Handler
{

    /**
     * Strategy for resolving elevator calls
     *
     * @var BuildingElevatorStrategy
     */
    protected $strategy;

    /**
     * The domain service for looking up available elevators
     *
     * @var LookupElevator
     */
    protected $lookup_service;

    /**
     * The domain service for moving elevators
     *
     * @var MoveElevator
     */
    protected $move_service;

    /**
     * A repository with flats
     *
     * @var FlatsRepository
     */
    protected $flats_repo;

    /**
     * Service Constructor
     *
     * Creates a Service for attending Elevator Calls,
     * which choses the right elevator by applying an Strategy
     *
     * @param Building $building The building with Elevators
     * @param ElevatorCalledStrategy $strategy The strategy to apply
     * @param StateService $service A state management service for the building
     *
     */
    public function __construct(FlatsRepository $flats_repo, LookupElevator $lookup_service, MoveElevator $move_service)
    {
        $this->flats_repo = $flats_repo;
        $this->lookup_service = $lookup_service;
        $this->move_service = $move_service;
    }

    /**
     * Handles a call to an elevator
     *
     * @internal Uses an strategy to determine which is the best elevator to move
     *           The strategy depends on the State of the Elevators in the Building
     *
     * @param ElevatorCallRequest $command
     * @return void
     */
    public function handle(ElevatorCallRequest $command)
    {
        $calling_flat = $this->flatsRepo->findByName($command->calling_flat, $this->building);
        if (! $calling_flat) {
            throw new \InvalidArgumentException(sprintf("Bad flat %s, for the elevator call", $command->calling_flat));
        }

        $destination_flat = $this->flatsRepo->findByName($command->destination_flat, $this->building);
        if (! $destination_flat) {
            throw new \InvalidArgumentException(sprintf("Bad flat %s, for the elevator destination", $command->destination_flat));
        }

        ($elevator = $this->lookup_service)($calling_flat, $destination_flat);
        ($this->move_service)($elevator, $calling_flat, $destination_flat);
    }
}
