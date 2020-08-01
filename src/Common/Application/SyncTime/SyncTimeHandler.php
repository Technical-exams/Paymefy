<?php namespace Proweb21\Elevators\Common\Application\SyncTime;

use Proweb21\Bus;
use Proweb21\Command;
use Proweb21\Elevators\Common\Model\SystemTime\SystemTimeProvider;
use Proweb21\CommandHandler;
use Proweb21\Elevators\Common\Infrastructure\Buses\TimeBus;
use Proweb21\Subscriber;

/**
 * Handler for SyncTime commands
 * 
 * @package Common
 */
final class SyncTimeHandler extends CommandHandler implements Subscriber
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
    public function handle(Command $command)
    {
        // Precondition $command must be a SyncTimeCommand
        if (! ($command instanceof SyncTimeCommand))
            return;

        $time = new \DateTime();
        $time->setTime($command->hour,$command->minute);
        $new_time = \DateTimeImmutable::createFromMutable($time);
        $this->provider->sync($new_time);
    }

    public function subscribe(Bus $bus)
    {
        if (! ($bus instanceof TimeBus))
            throw new \AssertionError("A SyncTimeHandler can only subscribe to TimeBuses");

        parent::subscribe($bus);
    }
}
