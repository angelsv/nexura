<?php

declare(strict_types=1);

// Composer
require dirname(__DIR__)  . '/vendor/autoload.php';

ini_set('display_errors', 'On');
error_reporting(E_ALL);

// Script timming
$rustart = getrusage();

// Debug
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

// Load files
$dotenv = Dotenv\Dotenv::createImmutable( dirname(__DIR__) );
$dotenv->load();

// TODO: medir la duración de toda la ejecución y luego mejorar el rendimiento
function rutime($ru, $rus, $index) {
    return ($ru["ru_{$index}.tv_sec"]*1000 + intval($ru["ru_{$index}.tv_usec"]/1000))
     -  ($rus["ru_{$index}.tv_sec"]*1000 + intval($rus["ru_{$index}.tv_usec"]/1000));
}