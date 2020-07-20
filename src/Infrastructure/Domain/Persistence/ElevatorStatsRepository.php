<?php namespace Proweb21\Elevator\Infrastructure\Domain\Persistence;

use Proweb21\Elevator\Infrastructure\Common\Persistence\ElevatorStatsStore;
use Proweb21\Elevator\Model\ElevatorStats;
use Proweb21\Elevator\Model\ElevatorStatsRepository as BaseRepository;

class ElevatorStatsRepository
    implements BaseRepository
{

    /**
     * How often stats are flushed to the store
     */
    const FLUSH_FRECUENCY = 100;


    /**
     * Where the elevator stats will be collected
     * while not flushed yet
     *
     * @var ElevatorStats[]
     */
    protected $collected_stats = [];


    /**
     * The most recent stats collected per elevator
     *
     * @var ElevatorStats[]
     */
    protected $most_recent_stats = [];


    /**
     * The store where to flush collected stats
     *
     * @var ElevatorStatsStore
     */
    protected $store;


    public function __construct(ElevatorStatsStore $store)
    {
        $this->store = $store;
    }

    public function __destruct()
    {
        $this->flushCollectedStatsToStore();
    }

    /**
     * {@inheritDoc}
     *
     */
    public function add(ElevatorStats $stats)
    {        
        $this->collected_stats[] = $stats;
        $this->most_recent_stats[$stats->elevator] = $stats;
        
        if (count($this->collected_stats) >= self::FLUSH_FRECUENCY)
            $this->flushCollectedStatsToStore();
    }

    /**
     * {@inheritDoc}
     *
     */
    public function findAll(bool $summarized): \Traversable
    {
        $result = [];
        
        $this->flushCollectedStatsToStore();
        if ($summarized)
            $result = $this->store->retrieveSummary();
        else
            $result = $this->store->retrieveMany();
        
        return $result;
    }

    /**
     * {@inheritDoc}
     *
     */
    public function last(string $elevator): ?ElevatorStats
    {
        $result = null;

        if (array_key_exists($elevator,$this->most_recent_stats))
            $result = $this->most_recent_stats[$elevator];
        else{
            $result = $this->store->retrieveNewest(["elevators"=>[$elevator]]);
            if (! is_null($result)){
                $this->most_recent_stats[$elevator] = $result;
            }
        }

        return $result;
    }


    /**
     * Flushes stats from collected_stats to the Stats Store
     *
     * @return void
     */
    protected function flushCollectedStatsToStore()
    {
        if (empty($this->collected_stats))
            return;
        else if (count($this->collected_stats) > 1)
            $this->store->appendMany($this->collected_stats);
        else
            $this->store->appendOne( reset($this->collected_stats) );

        // This effectively removes all events in the collected list
        array_splice($this->collected_stats, 0);
    }



}