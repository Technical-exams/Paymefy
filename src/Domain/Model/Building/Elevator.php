<?php namespace Proweb21\Elevator\Model\Building;

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
{

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
        $this->move($current_flat);
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
        if ("id" == $name) {
            return $this->getId();
        } elseif ("flat" == $name) {
            return $this->getFlat();
        }
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
     * @return Flat
     */
    public function getFlat(): Flat
    {
        return $this->flat;
    }

    /**
     * Elevator $flat setter
     *
     * @param Flat $flat
     * @return Elevator
     *
     * @throws \AssertionError if the flat is not in the same building
     */
    public function move(Flat $to_flat): Elevator
    {

        if (!is_null($this->flat) && $this->flat->building !== $to_flat->building) {
            throw new \AssertionError("This flat is not in the building");
        }
        $this->flat = $to_flat;
        return $this;
    }
}
