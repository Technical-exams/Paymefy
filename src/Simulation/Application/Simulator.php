<?php namespace Proweb21\Elevator\Simulation\Application;

use Proweb21\Elevator\Application\ApplicationService;

interface Simulator extends ApplicationService
{
    public function start();

    public function started() : bool;

    public function stop();
}
