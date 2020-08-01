<?php namespace Proweb21\Elevators\Simulation\Application;

/**
 * Trait implementing Simulation interface
 */
trait SimulationTrait
{

    /**
     * Flag indication if the simulation is started
     *
     * @var boolean
     */

    protected $started = false;


    /**
     * {@inheritDoc}
     *
     */
    public function start()
    {
        if ($this->started()) {
            return;
        }
        $this->started = true;

        $this->doRun();

        $this->started = false;
    }

    /**
     * {@inheritDoc}
     *
     */
    public function isStarted(): bool
    {
        return ($this->started);
    }

    /**
     * {@inheritDoc}
     *
     */
    public function stop()
    {
        $this->started = false;
    }

    /**
     * Runs the simulation
     */
    protected function doRun()
    {
        throw new \RuntimeException("You must override `doRun` method from SimulationTrait in the class using it");
    }
}
