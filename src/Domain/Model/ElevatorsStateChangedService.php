<?php namespace Proweb21\Elevator\Model;

use Proweb21\Elevator\Domain\ObserverTrait;
use Proweb21\Elevator\Events\Observer;
use Proweb21\Elevator\Model\ElevatorStatsRepository as Repository;

/**
 * Service for managing the logic behind changes on the ElevatorsState
 * 
 */
class ElevatorsStateChangedService
    implements Observer
{

    use ObserverTrait;
    

    /**
     * The repository where to save events occurred
     *
     * @var ElevatorStatsRepository
     */
    protected $repository;


    /**
     * The Building as reference for event received info
     *
     * @var Building
     */
    protected $building;

    /**
     * Creates a new instance of Service
     *
     * @param ElevatorStatsRepository $repository The repository where to save events observed
     * @param Building $building The Building from where to check the flats ids
     */
    public function __construct(Repository $repository, Building $building)
    {
        $this->building = $building;
        $this->repository = $repository;

        $this->observe(ElevatorsStateChanged::class, 'updateStats');
    }

    /**
     * Reacts to an ElevatorsStateChanged event updating the Stats of the Elevator which altered the State
     *
     * @param ElevatorsStateChanged $event
     * @return void
     */
    public function updateStats(ElevatorsStateChanged $event)
    {
        // Flat is set from identifier to position
        $flat_no = array_search($event->elevator_flat,$this->building->getFlats());


        $stats = new ElevatorStats( $event->elevator_moved, 
                                    $flat_no, 
                                    $event->elevator_flat, 
                                    $event->flats_moved,
                                    $this->calculateAccumulated($event->elevator_moved,$event->flats_moved),
                                    $event->time                                 
        );

        $this->repository->add($stats);
    }

    /**
     * Undocumented function
     * 
     * @internal This may be better (more SOLID) implemented by an strategy or external service consuming the repository
     * and the scalar data
     * 
     * @param string $elevator
     * @param integer $new_movement
     * @return int
     */
    protected function calculateAccumulated(string $elevator, int $new_movement): int
    {
        $result = $new_movement;

        $stats = $this->repository->last($elevator);
        if (!is_null($stats))
            $result += $stats->total_moves;
        
        return $result;
    }

}

