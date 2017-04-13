<?php

ini_set('display_errors', 1);

include 'vendor/autoload.php';

use Akane\Core\Runner;

try {
    include __DIR__.'/app/common.php';
    $runner = new Runner;
    $runner->initClass($classes);
    $runner->boot();
} catch (Exception $e) {
    echo 'Internal Error: '.$e->getMessage();
}