<?php

require __DIR__ . '/core/Console.php';

array_shift($argv);
$console = new Console();

if (in_array('migrate', $argv)) {
    if (count($argv) == 1) {
        $console->migrate();
    } elseif (count($argv) == 2) {
        $console->migrate($argv[1]);
    }
}