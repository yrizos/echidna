<?php


$client   = new MongoClient();
$database = $client->selectDB('echidna_benchmark');
$loops    = 5000;

function writeLog($file, $loops, $time)
{
    $mem  = memory_get_peak_usage(true);
    $avg  = $time / $loops;
    $data = [$loops, $time, $avg, $mem];
    $data = implode(',', $data) . "\n";
    $dir  = dirname($file);

    if (!is_dir($dir)) mkdir($dir, 0777);

    file_put_contents($file, $data, FILE_APPEND);

    echo 'time: ' . $time . PHP_EOL;
    echo 'avg: ' . $avg . PHP_EOL;
    echo '$mem: ' . $mem . PHP_EOL;
}

function getData($i)
{
    return [
        'string'  => 'string' . $i,
        'integer' => $i,
        'float'   => (float) $i,
        'array'   => array_fill(0, 10, 'value')
    ];
}