<?php namespace Proweb21\Elevator\Infrastructure\Domain\Persistence;

use Proweb21\Elevator\Infrastructure\Common\Persistence\DataBaseConnection;
use Proweb21\Elevator\Infrastructure\Common\Persistence\ElevatorStatsStore;
use Proweb21\Elevator\Model\ElevatorStats;


/**
 * Adapter for the ElevatorStatsStore Port
 */
class SQLiteElevatorStatsStore
    implements ElevatorStatsStore
{

    const DATETIME_FORMAT = "Y-m-d H:i:00";

    /**
     * Connection to Persistence Database
     *
     * @var DataBaseConnection
     */
    protected $conn;

    public function __construct(DataBaseConnection $conn)
    {
        $this->conn = $conn;
    }

    public function __destruct()
    {
        if ($this->conn->connected())
            $this->conn->close();
    }

    protected function connect() : bool
    {
        if (!$this->conn->connected())
            $this->conn->open();
        return $this->conn->connected();
    }

    /**
     * Transforms ElevatorStats to SQL values for insert
     *
     * @internal This must be implemented as a DataTransformer
     * @param ElevatorStats $stats
     * @return string
     */
    protected function toSQLValues(ElevatorStats $stats) : string
    {
        
        $elevator = $stats->elevator;
        $flat = $stats->flat_name;
        $accum = $stats->total_moves;
        $displacement = $stats->last_movement;
        $time = $stats->last_update->format(self::DATETIME_FORMAT);

        return "(\"${time}\",\"${elevator}\",${flat},${accum},${displacement})\n";
    }

    /**
     * Prepares an insert statement for a set of values
     *
     * @internal This must be implemented as a DataTransformer
     * @param string $values
     * @return string
     */
    protected function toSQLInsert(string $values) : string
    {
        return "INSERT INTO `elevator_stats` (`time`, `elevator`, `stopped_at`, `accum_moves`, `last_move`) ". 
               "VALUES ${values}";
    }

    /**
     * Transforms criteria to a WHERE condition
     *
     * @internal This must be solved using the Specification pattern
     * @param array $criteria
     * @return string
     */
    protected function toSQLWhere(array $criteria): string
    {
        $result = "TRUE";

        if (array_key_exists("elevators",$criteria)){
            $elevators = (array)($criteria["elevators"]);
            if (count($elevators)){
                $result = "`elevator` IN (\"".implode("\", \"", $elevators)."\")";
            }
        }

        return $result;
    }

    /**
     * Transforms a query result to a traversable result
     *
     * @internal This must be solved using a DataTransformer
     * @param \SQLite3Result $result
     * @return \Traversable
     */
    protected function toResult(\SQLite3Result $result): \Traversable
    {
        while ( FALSE !==  ($row = $result->fetchArray(\SQLITE3_ASSOC))) 
            yield $this->toSingleResult($row);
    }

    /**
     * Transforms a query result row to an ElevatorStats
     *
     * @internal This must be solved using a DataTransformer
     * @internal Flat position is never stored, so flat id ("stopped_at") is used instead 
     * 
     * @param array $row A query result row
     * @return ElevatorStats
     */
    protected function toSingleResult(array $row) : ElevatorStats
    {
        $last_update = \DateTimeImmutable::createFromFormat(self::DATETIME_FORMAT,$row['time']);
        // stopped_at is reused for flat id and flat position
        return new ElevatorStats($row['elevator'],$row['stopped_at'], 
                                 $row['stopped_at'],$row['last_move'], 
                                 $row['accum_moves'],$last_update);
    }


    /**
     * {@inheritDoc}
     *
     */
    public function appendOne(ElevatorStats $stats): bool
    {
        if (!$this->connect()) return FALSE;

        $values = $this->toSQLValues($stats);
        $insert = $this->toSQLInsert($values);        

        try{
            //$this->conn->execute("BEGIN TRANSACTION;");
            $this->conn->execute($insert);
            //$this->conn->execute("COMMIT;");
            return true;
        }catch(\Throwable $error){
            //$this->conn->execute("ROLLBACK;");
            return false;
        }
        
    }

    /**
     * {@inheritDoc}
     *
     */
    public function appendMany(array $queue_of_stats) : bool
    {
        if (!$this->connect()) return FALSE;

        $values = [];

        foreach($queue_of_stats as $stats){
            $values[] = $this->toSQLValues($stats);
        }
        $values = implode(', ', $values);

        $insert = $this->toSQLInsert($values);
                
        try{
            $this->conn->execute("BEGIN TRANSACTION;");
            $this->conn->execute($insert);
            $this->conn->execute("COMMIT;");
            return true;
        }catch(\Throwable $error){
            $this->conn->execute("ROLLBACK;");
            return false;
        }
    }

    /**
     * {@inheritDoc}
     *
     */    
    public function retrieveMany(array $criteria = []): \Traversable
    {
        $where = $this->toSQLWhere($criteria);
        $query = "
            SELECT *
            FROM `elevator_stats`
            WHERE {$where}            
            ORDER BY id ASC";
        
        $this->connect();
        $query_result = $this->conn->query($query);
        
        return $this->toResult($query_result);
    }

    /**
     * {@inheritDoc}
     *
     */
    public function retrieveNewest(array $criteria = []): ?ElevatorStats
    {
        $where = $this->toSQLWhere($criteria);
        $query = "
            SELECT *
            FROM `elevator_stats`
            WHERE {$where}            
            ORDER BY time DESC
            LIMIT 1";
        
        $this->connect();
        $query_result = $this->conn->query($query);        
        
        $result = $query_result->fetchArray(\SQLITE3_ASSOC);
        
        return ($result !== FALSE) ? $this->toSingleResult($result) : null;
    }

    /**
     * {@inheritDoc}
     *
     */    
    public function retrieveSummary(): \Traversable
    {
        $query = "
            SELECT *
            FROM `elevator_stats_summary`";

        $this->connect();
        $query_result = $this->conn->query($query);

        return $this->toResult($query_result);
    }
}
