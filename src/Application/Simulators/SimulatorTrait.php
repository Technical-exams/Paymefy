<?php namespace Proweb21\Elevator\Application\Simulators;

/**
 * Trait implementing Simulator interface
 */
trait SimulatorTrait
{

    /**
     * Flag indication if the simulator is started
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
        if ($this->started()) return;
        $this->started = true;

        $this->doRun();

        $this->started = false;
    }

    /**
     * {@inheritDoc}
     *
     */
    public function started(): bool
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


}