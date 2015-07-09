<?php

require_once("bootstrap.php");

use Monolog\Logger;
use Monolog\Handler as LogHandler;
use Monolog\Formatter as LogFormatter;

$database = new \Thru\ActiveRecord\DatabaseLayer(array(
  'db_type'     => 'Mysql',
  'db_hostname' => 'localhost',
  'db_port'     => '3306',
  'db_username' => 'travis',
  'db_password' => 'travis',
  'db_database' => 'starterapp_test',
));

// Configure Logging
$fileLoggerHandler = new LogHandler\StreamHandler(__DIR__ . "/build/logs/starterapp." . date('Y-m-d') . '.log', null, null, 0664);
$monologHandlers = [$fileLoggerHandler];
$monolog = new Logger("StarterApp", $monologHandlers);
$database->setLogger($monolog);

// Configure Redis.
$redis = new Redis();
$redis->pconnect("localhost", 6379, 0.0);

$cache = new Doctrine\Common\Cache\RedisCache();
$cache->setRedis($redis);

$database->setCache($cache);

// Set instance.
\Thru\ActiveRecord\DatabaseLayer::setInstance($database);
