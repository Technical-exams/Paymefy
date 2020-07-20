<?php namespace Proweb21\Elevator\Model;

/**
 * DTO for persisting Elevator Stats taken during application execution
 */
final class ElevatorStats
{
    /**
     * Which is the elevator
     *
     * @var string
     */
    protected $elevator;

    /**
     * Which is the identifier of the flat on the building
     * where elevator was when the stat was taken
     *
     * @var int
     */
    protected $flat_name;

    /**
     * Which is the flat position in the building
     *
     * @var int
     */
    protected $flat_no;

    /**
     * How many flats has moved the elevator during the application execution
     *
     * @var int
     */
    protected $total_moves;

    /**
     * How many flats has the elevator moved in its last trip
     *
     * @var int
     */
    protected $last_movement;

    /**
     * When the stats were taken
     *
     * @var \DateTimeImmutable
     */
    protected $last_update;


    public function __construct(string $elevator, int $flat_no, int $flat_name, int $moved, int $accumulated, \DateTimeImmutable $last_update)
    {
        $this->elevator = $elevator;
        $this->flat_no = $flat_no;
        $this->flat_name = $flat_name;
        $this->total_moves = $accumulated;
        $this->last_movement = $moved;
        $this->last_update = $last_update;
    }

    public function __get($property){
        if (property_exists(get_class($this),$property))
            return $this->{$property};
    }

}