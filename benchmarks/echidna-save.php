<?php
include_once __DIR__ . '/../vendor/autoload.php';
include_once 'bootstrap.php';

$time = microtime(true);

class BenchmarkDocument extends \Echidna\Document
{
    protected static $collection = 'echidna_save';

    public static function fields()
    {
        return [
            'string'  => ['type' => 'string'],
            'integer' => ['type' => 'integer'],
            'float'   => ['type' => 'float'],
            'array'   => ['type' => 'raw'],
        ];
    }
}

$mapper   = new \Echidna\Mapper($database, 'BenchmarkDocument');
$document = new BenchmarkDocument();

for ($i = 0; $i < $loops; $i++) {
    $document->setData(getData($i));
    $mapper->save($document);
}

$time = microtime(true) - $time;

writeLog('logs/echidna-save.log', $loops, $time);

