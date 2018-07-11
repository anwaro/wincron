<?php
/**
 * Created by PhpStorm.
 * User: Łukasz Micał
 * Date: 10.07.2018
 * Time: 12:35
 */

require_once '../vendor/autoload.php';

use anwaro\wincron\Cron;

$config = [
    'path' => __DIR__
];

$cron = new Cron($config);
$cron->run();
