<?php namespace Proweb21\Elevators\Model\State;

use Proweb21\Elevators\Model\Building\Flat;

/**
 * Collection of FlatState indexed by Flat
 *
 * Acts simliar to an \ArrayObject
 * @see https://www.php.net/manual/es/class.arrayobject.php
 *
 */
final class FlatStateCollection implements \ArrayAccess
{

    /**
     * The inner collection of FlatState
     *
     * @var FlatState[]
     */
    protected $states = [];

 
    /**
     * Seeks an returns the FlatState placed at the relative $offset position
     * from given flat
     *
     * @param Flat $flat
     * @param integer $offset Positive or negative offset number
     * @return FlatState|FALSE
     */
    public function seek(Flat $flat, int $offset = 0)
    {
        $result = false;

        if (! ($flat instanceof Flat)) {
            throw new \InvalidArgumentException("Trying to get a FlatState from something what is not a Flat");
        }

        if (array_key_exists($flat->position + $offset, $this->states)) {
            $result = $this->states[$flat->position + $offset];
        }

        return $result;
    }

    /**
     * Gets a FlatState given a flat
     *
     * @param Flat $flat
     * @return FlatState|FALSE
     */
    public function offsetGet($flat)
    {
        $result = false;

        if (! ($flat instanceof Flat)) {
            throw new \InvalidArgumentException("Trying to get a FlatState from something what is not a Flat");
        }

        if (array_key_exists($flat->position, $this->states)) {
            $result = $this->states[$flat->position];
        }

        return $result;
    }

    /**
     * Sets the FlatState for a given flat
     *
     * @param int $flat
     * @param FlatState $state
     * @return void
     * @throws \InvalidArgumentException when $state is not an instance of FlatState
     */
    public function offsetSet($flat, $state): void
    {
        if (! ($state instanceof FlatState)) {
            throw new \InvalidArgumentException("Argument \$state is not an instance of FlatState");
        }

        if (! ($flat instanceof Flat)) {
            throw new \InvalidArgumentException("Trying to set a FlatState for something what is not a Flat");
        }

        $this->states[$flat->position] = $state;
        
        ksort($this->states, \SORT_NUMERIC);
    }

    /**
     * Checks if the FlatState of a flat is in the collection
     *
     * @param Flat $flat
     * @return boolean
     */
    public function offsetExists($flat): bool
    {
        return array_key_exists($flat->position, $this->states);
    }

    public function offsetUnset($flat): void
    {
        throw new \RuntimeException("Not implemented");
    }
}
