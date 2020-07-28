<?php namespace Proweb21\Elevator\Application\SyncTime;

use Proweb21\Elevator\Model\SystemTime\SystemTimeProvider;
use Proweb21\Handler;

/**
 * Handler for SyncTime commands
 * 
 * @package Common
 */
final class SyncTimeHandler implements Handler
{

    /**
     * The system time provider
     *
     * @var SystemTimeProvider
     */
    protected $provider;


    public function __construct(SystemTimeProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Handles SyncTime Commands
     *
     * @param SyncTimeCommand $command
     * @return void
     */
    public function handle(SyncTimeCommand $command)
    {
        $time = new \DateTime();
        $time->setTime($command->hour,$command->minute);
        $new_time = \DateTimeImmutable::createFromMutable($time);
        $this->provider->sync($new_time);
    }
}
