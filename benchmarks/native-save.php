<?php

include_once 'bootstrap.php';

$collection = $database->native_save;
$time       = microtime(true);

for ($i = 0; $i < $loops; $i++) {
    $data = getData($i);
    $collection->save($data);
}

$time = microtime(true) - $time;

writeLog('logs/native-save.log', $loops, $time);