<?php namespace Proweb21;

/**
 * Event occurred in a class which is Observable
 *
 * This kind of events are emitted by Observable classes via
 * @see Observable::notify
 *
 */
abstract class ObservableEvent implements Event
{

    /**
     * The observable subject responsible of emitting this event
     *
     * @var Observable
     */
    protected $subject;

    /**
     * Constructor
     *
     * @param Observable $subject
     */
    public function __construct($subject)
    {
        $this->subject = $subject;
    }

    /**
     * Gets the Observable Object where this event ocurred
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * {@inheritDoc}
     *
     */
    abstract public function getTime(): \DateTimeImmutable;
}
