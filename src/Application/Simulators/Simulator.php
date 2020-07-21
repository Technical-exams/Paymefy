<?php namespace Proweb21\Elevator\Application\Simulators;

interface Simulator
{
    public function start();

    public function started() : bool;

    public function stop();
}