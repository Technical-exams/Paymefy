<?php namespace Proweb21\Time;

use Proweb21\EventHandler;

/**
 * Singleton class intended to provide the current application time
 * as if it was an actual system clock
 * 
 * This class is necessary due time pass simulations
 */
final class MinutePassedHandler
    implements EventHandler
{

    /**
     * Handles MinutePassedEvent
     *
     * @param MinutePassed $event
     * @return void
     */
    public function handle(MinutePassed $event){
        SystemTime::instance()->setCurrentTime($event->time);
    }
}
