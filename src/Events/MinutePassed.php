<?php namespace Proweb21\Elevator\Events;


final class MinutePassed
    implements Event
{
    
    use EventTrait;

    /**
     * Constructor
     * 
     * Creates a new instance of a MinutePassed Event
     *
     * @param int $hour
     * @param int $minute
     * @return void
     */
    public function __construct(int $hour, int $minute)
    {
        $time = new \DateTime();
        $time->setTime($hour,$minute);
        $this->time = \DateTimeImmutable::createFromMutable($time);
    }

    
}