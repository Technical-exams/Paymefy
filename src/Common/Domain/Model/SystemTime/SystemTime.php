<?php namespace Proweb21\Elevators\Common\Model\SystemTime;

/**
 * Singleton class service intended to provide the current application time
 * as if it was an actual system clock, providing the Events
 *
 * This class is useful for clock simulations
 * @package Common
 */
final class SystemTime
{
    /**
     * The time
     *
     * @var \DateTimeImmutable
     */
    protected $time;
    

    /**
     * Protected constructor
     * to protect the singleton
     */
    public function __construct(\DateTimeImmutable $time = null)
    {
        $this->time = $time ? : new \DateTimeImmutable();
    }
    

    /**
     * Gets the time
     *
     * @return \DateTimeImmutable
     */
    public function getTime(): \DateTimeImmutable
    {
        return $this->time;
    }
}
