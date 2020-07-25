<?php namespace Proweb21\Elevator\Model\Building;

use Proweb21\Elevator\Domain\ObservableTrait;
use Proweb21\Elevator\Events\Observable;

/**
 * Elevator is an Entity gathering a flat
 * 
 * Each elevator is identified by its id,
 * in this case their manufacturer serial number
 * 
 * @property-read string $id
 * @property-read Flat $flat
 */
final class Elevator
    implements Observable
{

    use ObservableTrait;

    /**
     * Flat where the elevator is stopped
     *
     * @var Flat
     */
    protected $flat;


    /**
     * Elevator Manufacturer Serial Number
     *
     * @var string
     */
    protected $serial_no;

    /**
     * Constructor
     *
     * @param Flat $initial_flat
     */
    public function __construct(Flat $current_flat)
    {
        $this->setFlat($current_flat);
        $this->serial_no = uniqid();
    }

    /**
     * Magic getter for getters shortcutting
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        if ("id" == $name)
            return $this->id();
        elseif ("flat" == $name)
            return $this->flat();
    }

    /**
     * Elevator identifier getter
     * 
     * This should be the manufacturer serial number
     *
     * @return string
     */
    public function id(): string
    {
        return $this->serial_no;
    }

    /**
     * Elevator $flat getter
     *
     * @return Flat
     */
    public function flat(): Flat
    {
        return $this->flat;
    }

    /**
     * Elevator $flat setter
     *
     * @param Flat $flat
     * @return Elevator
     */
    public function setFlat(Flat $flat): Elevator
    {
        $previous_flat = $this->flat;
        $this->flat = $flat;
        if (!is_null($previous_flat) && ($previous_flat !== $flat))
            $this->publishFlatChanged($previous_flat);
        return $this;
    }

    /**
     * Notifies observers an ElevatorFlatChanged domain event
     *
     * @param Flat $previous_flat
     * @return void
     */
    protected function publishFlatChanged($previous_flat){
        $this->publish( 
            new ElevatorFlatChanged($this->id,
                                    $previous_flat->position, 
                                    $this->current_flat->position) 
        );
    }

}