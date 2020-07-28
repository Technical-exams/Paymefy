<?php namespace Proweb21;

interface Bus extends Dispatcher
{

    /**
     * The name which the bus may be reachable in the application
     *
     * @return string
     */
    public function name():string;
}
