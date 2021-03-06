<?php
namespace EchidnaTest;

class Base extends \PHPUnit_Framework_TestCase
{

    /** @var  \MongoDB */
    protected $database;

    protected $data = [];

    public function setUp()
    {
        $connection     = new \MongoClient('mongodb://localhost:27017', ['connect' => true, 'connectTimeoutMS' => -1, 'journal' => true]);
        $this->database = $connection->selectDB('echidna_test');
        $this->database->drop();

        for ($i = 0; $i < 5; $i++) {
            $this->data[] = 'value ' . $i;
        }
    }

} 