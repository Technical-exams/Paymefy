<?php namespace Proweb21;

trait DispatcherTrait
{
    protected $subscribers = [];

    /**
     * {@inheritDoc}
     *
     */
    public function subscribe(Subscriber $subscriber)
    {
        if (! in_array($subscriber, $this->subscribers)) {
            $this->subscribers[] = $subscriber;
        }                
    }

    /**
     * {@inheritDoc}
     *
     */
    public function clearSubscribers()
    {
        $this->subscribers = [];
    }

    /**
     * {@inheritDoc}
     *
     */
    public function dispatch($object)
    {        
        foreach ($this->subscribers as $subscriber) {
            $subscriber->handle($object);
        }        
    }
}
