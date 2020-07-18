<?php namespace Proweb21\Elevator\Events;


interface EventBus
    extends EventDispatcher
{

    /**
     * The name which the bus may be reachable in the application
     *
     * @return string
     */
    public function name():string;

}