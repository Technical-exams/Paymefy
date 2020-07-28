<?php namespace Proweb21\Elevator\Domain\Services;

use Proweb21\Elevator\Domain\Events\ElevatorHasMoved;
use Proweb21\Elevator\Domain\ObserverTrait;
use Proweb21\Elevator\Events\Observer;
use Proweb21\Elevator\Model\Building\ElevatorsRepository;
use Proweb21\Elevator\Model\Building\FlatsRepository;
use Proweb21\Elevator\Model\ElevatorMove\ElevatorMove;
use Proweb21\Elevator\Model\ElevatorMove\ElevatorMovesRepository;

/**
 * Service responsible of recording the elevator movements when an elevator has moved
 *
 */
final class ElevatorMovedService implements Observer
{
    use ObserverTrait;

    /**
     * The Write Model Repository of ElevatorMoves
     *
     * @param ElevatorMovesRepository $repository
     */
    protected $moves_repository;

    /**
     * The Repository of Elevators
     *
     * @var ElevatorsRepository
     */
    protected $elevators_repository;

    /**
     * The Repository of Flats
     *
     * @var FlatsRepository
     */
    protected $flats_repository;

    public function __construct(ElevatorMovesRepository $moves_repository, ElevatorsRepository
    $elevators_repository, FlatsRepository $flats_repository)
    {        
        $this->flats_repository = $flats_repository;
        $this->moves_repository = $moves_repository;
        $this->elevators_repository = $elevators_repository;
        $this->observe(ElevatorHasMoved::class,'onElevatorMoved');
    }

    /**
     * Reacts to ElevatorHasMoved Domain Event writing the movement
     *
     * @param ElevatorHasMoved $event
     * @return void
     */
    public function onElevatorMoved(ElevatorHasMoved $event)
    {
        $moved_at = $event->time;
        $from_flat = $this->flats_repository->findOneByPosition($event->previous_flat,$event->building);
        $to_flat = $this->flats_repository->findOneByPosition($event->current_flat,$event->building);
        $elevator = $this->elevators_repository->findOne($event->elevator_id);

        if ( is_null($elevator)){
            throw new \RuntimeException("An unknown elevator has moved. Details:\n".
                                        "elevator: ${event->elevator_id}\n".
                                        "building: ${event->building}"
                                        );
        }
        if ( is_null($from_flat)){
            throw new \RuntimeException("An elevator has moved from an unknown flat. Details:\n".
                                        "elevator: ${event->previous_flat}\n".
                                        "building: ${event->building}"
                                        );
        }
        if ( is_null($from_flat)){
            throw new \RuntimeException("An elevator has moved to an unknown flat. Details:\n".
                                        "elevator: ${event->current_flat}\n".
                                        "building: ${event->building}"
                                        );
        }

        $elevatorMove = new ElevatorMove($elevator,$to_flat,$from_flat,$moved_at);
        $this->moves_repository->add($elevatorMove);
    }
}
