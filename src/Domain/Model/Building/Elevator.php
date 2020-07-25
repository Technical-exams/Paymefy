<?php namespace Proweb21\Elevator\Model\Building;

use Proweb21\Elevator\Domain\ObservableTrait;
use Proweb21\Elevator\Events\Observable;

/**
 * Elevator is an Entity gathering a flat
 * 
 * Each elevator is identified by its id,
 * in this case their manufacturer serial number
 * 
 * @property string $id
 * @property int $flat
 */
final class Elevator
    implements Observable
{

    use ObservableTrait;

    /**
     * Flat where the elevator is stopped
     *
     * @var int
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
     * @param integer $initial_flat
     */
    public function __construct(int $initial_flat)
    {
        $this->setFlat($initial_flat);
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
            return $this->getId();
        elseif ("flat" == $name)
            return $this->getFlat();
    }

    /**
     * Elevator identifier getter
     * 
     * This should be the manufacturer serial number
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->serial_no;
    }

    /**
     * Elevator $flat getter
     *
     * @return integer
     */
    public function getFlat(): int
    {
        return $this->flat;
    }

    /**
     * Elevator $flat setter
     *
     * @param integer $flat
     * @return Elevator
     */
    public function setFlat(int $flat): Elevator
    {
        $previous_flat = $this->flat;
        $this->flat = $flat;
        if (!is_null($previous_flat) && ($previous_flat !== $flat))
            $this->publishFlatChanged($previous_flat);
        return $this;
    }

    /**
     * Notifies observers a ElevatorFlatChanged domain event
     *
     * @param int $previous_flat
     * @return void
     */
    protected function publishFlatChanged($previous_flat){
        $this->publish(new ElevatorFlatChanged($this->id,$previous_flat,$this->flat) 
        );
    }

}