<?php namespace Proweb21\Elevators\Common\Infrastructure\Persistence;

/**
 * DBAL Adapter for SQLite3 databases
 */
final class SQLite3Connection implements DataBaseConnection
{
    protected $file;

    protected $password;

    /**
     * SQLite Database Connection Driver
     *
     * @var \SQLite3
     */
    protected $driver;

    public function __construct(string $file, string $password = "")
    {
        if (file_exists($file)) {
            $this->file = $file;
        }

        $this->password = $password;


        set_error_handler(function ($severity, $message, $file, $line) {
            throw new \ErrorException($message, $severity, $severity, $file, $line);
        }, \E_WARNING);

        try {
            $this->open();
        } finally {
            restore_error_handler();
        }
    }


    public function open()
    {
        if ($this->connected()) {
            return;
        }

        try {
            $this->driver = new \SQLite3($this->file, \SQLITE3_OPEN_READWRITE, $this->password);
        } catch (\ErrorException $error) {
            $this->driver = null;
            return;
        }

        if (! empty($this->password)) {
            try {
                $this->driver->loadExtension('sqlcipher.so');
            } catch (\ErrorException $error) {
                throw new \RuntimeException(
                    "Please make sure you have a compiled sqlcipher extension for SQLite in your sqlite extension directory, ".
                                            "and configured sqlite3.extension_dir in your php.ini file\n".
                                            "sqlite3.extension_dir=".(ini_get('sqlite3.extension_dir')?:"<not configured>")
                );
            }
        }
        // JOURNAL MODE IS SET TO WALL
        // PLEASE READ https://www.sqlite.org/wal.html
        $this->driver->exec('PRAGMA journal_mode = wal;');
    }

    public function connected(): bool
    {
        return !is_null($this->driver);
    }

    public function close()
    {
        try {
            $this->driver->close();
        } finally {
            unset($this->driver);
            $this->driver = null;
        }
    }


    public function query(string $query)
    {
        if (! $this->connected()) {
            return false;
        }

        set_error_handler(function ($severity, $message, $file, $line) {
            throw new \ErrorException($message, $severity, $severity, $file, $line);
        }, \E_ERROR | E_WARNING | E_NOTICE);

        try {
            $result = $this->driver->query($query);
            ;
            return $result;
        } finally {
            restore_error_handler();
        }
    }

    public function execute(string $stmt)
    {
        if (! $this->connected()) {
            return false;
        }

        set_error_handler(function ($severity, $message, $file, $line) {
            throw new \ErrorException($message, $severity, $severity, $file, $line);
        }, \E_ERROR | E_WARNING | E_NOTICE);

        try {
            $this->driver->exec($stmt);
            ;
            return true;
        } finally {
            restore_error_handler();
        }
    }
}
