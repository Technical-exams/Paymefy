<?php namespace Proweb21;

trait DispatcherTrait
{
    protected $subscriptions = [];

    /**
     * {@inheritDoc}
     *
     */
    public function subscribe(string $dispatchable_class, Handler $handler)
    {
        if (!array_key_exists($dispatchable_class, $this->subscriptions)) {
            $this->subscriptions[$dispatchable_class] = [];
        }
        
        $this->subscriptions[$dispatchable_class][] = $handler;
    }

    /**
     * {@inheritDoc}
     *
     */
    public function clearSubscribers()
    {
        $this->subscriptions = [];
    }

    /**
     * {@inheritDoc}
     *
     */
    public function dispatch(Dispatchable $object)
    {
        if (array_key_exists(get_class($object), $this->subscriptions)) {
            foreach ($this->subscriptions[get_class($object)] as $handler) {
                $handler->handle($object);
            }
        }
    }
}
