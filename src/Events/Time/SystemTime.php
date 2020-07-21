<?php namespace Proweb21\Elevator\Events\Time;

use Proweb21\Elevator\Events\EventHandler;
use Proweb21\Elevator\Events\MinutePassed;

/**
 * Singleton class intended to provide the current application time
 * as if it was an actual system clock
 * 
 * This class is necessary due time pass simulations
 */
final class SystemTime
    implements EventHandler
{
    /**
     * Which time the system is in currently
     *
     * @var \DateTimeImmutable
     */
    protected static $time;

    protected static $instance;

    /**
     * Singleton class instance accessor
     */
    public static function instance() : SystemTime
    {
        self::$instance = self::$instance ? : new static();
        return self::$instance;
    }

    /**
     * Protected constructor
     * to protect the singleton
     */
    protected function __construct(){      
    }
    
    /**
     * Handles the pass of time events
     *
     * @param MinutePassed $event
     * @return void
     */
    public function handle(MinutePassed $event){
        self::$time = $event->time;
    }

    /**
     * Gets the clock time
     *
     * @return \DateTimeImmutable
     */
    public function getCurrentTime(): \DateTimeImmutable
    {
        return self::$time ? : new \DateTimeImmutable();
    }

}
