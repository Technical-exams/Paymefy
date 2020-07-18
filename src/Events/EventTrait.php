<?php namespace Proweb21\Elevator\Events;

/**
 * Trait implementing the Event interface
 * @property-read \DateTimeImmutable $time
 */
trait EventTrait
{

    /**
     * Magic getter
     *
     * @param string $property
     * @return mixed
     */
    public function __get(string $property){
        if ($property === 'time')
            return $this->getTime();
    }

    /**
     * When the event ocurred
     *
     * @var \DateTimeImmutable
     */
    protected $time;    

    /**
     * {@inheritDoc}
     */
    public function getTime(): \DateTimeImmutable
    {
        return $this->time;
    }

    /**
     * Sets the event time
     *
     * @param string|\DateTimeImmutable $time
     * @return void
     */
    protected function setTime($time)
    {
        if (is_string($time))
            $this->time = new \DateTimeImmutable($time);
        elseif ($time instanceof \DateTimeImmutable)
            $this->time = $time;
        else
            throw new \InvalidArgumentException("Unexpected time value for event of class ".get_class($this));
    }
}