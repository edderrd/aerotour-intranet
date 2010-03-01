<?
session_start();
global $configuration;

//PDO related settings
$configuration['pdoDriver']= 'sqlite';
$configuration['sqliteDatabase']= '../data/flights.sqlite';

define('DB_NAME', '../data/flights.sqlite');
define('DB_TABLE', 'schedule');

?>