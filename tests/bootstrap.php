<?php

use Dotenv\Dotenv;
use PHPUnit\Framework\TestSuite;
use PHPUnit\TextUI\DefaultResultPrinter;
use PHPUnit\TextUI\TestRunner;

require __DIR__.'/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

return;

