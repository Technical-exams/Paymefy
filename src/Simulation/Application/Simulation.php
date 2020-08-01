<?php namespace Proweb21\Elevators\Simulation\Application;

use Proweb21\Elevators\Common\Application\ApplicationService;

/**
 * Simulation of the ocurrence of some dispatchable objects
 * 
 * A simulation reproduces a context 
 * where dispatchable objects are created and broadcasted to a Bus
 * 
 * Simulations can be started or stopped
 * @see Simulation::start
 * @see Simulation::stop
 * 
 */
interface Simulation extends ApplicationService
{
    public function start();

    public function isStarted() : bool;

    public function stop();
}
