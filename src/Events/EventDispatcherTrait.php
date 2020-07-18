<?php namespace Proweb21\Elevator\Events;


trait EventDispatcherTrait
{

    protected $subscriptions = [];

    /**
     * {@inheritDoc}
     *
     */
    public function subscribe(string $event_class, EventHandler $handler)
    {
        if (!array_key_exists($event_class,$this->subscriptions))
            $this->subscriptions[$event_class] = [];
        
        $this->subscriptions[$event_class][] = $handler;        
    }

    /**
     * {@inheritDoc}
     *
     */
    public function clearSubscribers(){
        $this->subscriptions = [];
    }

    /**
     * {@inheritDoc}
     *
     */
    public function dispatch(Event $event)
    {
        if (array_key_exists(get_class($event),$this->subscriptions))        
            foreach ($this->subscriptions[get_class($event)] as $handler) 
                $handler->handle($event);            
    }

}