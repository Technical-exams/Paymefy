<?php namespace Proweb21\Elevators\Common\Infrastructure\Persistence;

/**
 * DBAL Port for specific driver database connection Adapters
 */
interface DataBaseConnection
{
    public function open();

    public function connected(): bool;

    public function close();

    public function query(string $query);

    public function execute(string $stmt);
}